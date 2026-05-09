<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::createFromFormat('Y-m-d', $request->start_date)
            : Carbon::now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::createFromFormat('Y-m-d', $request->end_date)
            : Carbon::now()->endOfDay();

        $query = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->with(['items.product', 'cashier', 'register']);

        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('order_type') && $request->order_type !== 'all') {
            $query->where('order_type', $request->order_type);
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('cashier') && $request->cashier !== 'all') {
            $query->where('user_id', $request->cashier);
        }

        $sales = $query->orderBy('created_at', 'desc')->paginate(15);

        $summary = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_transactions,
                SUM(total) as total_revenue,
                SUM(paid_amount) as total_paid,
                AVG(total) as average_transaction,
                MIN(total) as min_transaction,
                MAX(total) as max_transaction
            ')
            ->when(
                $request->payment_method !== 'all' && $request->filled('payment_method'),
                fn($q) => $q->where('payment_method', $request->payment_method)
            )
            ->when(
                $request->order_type !== 'all' && $request->filled('order_type'),
                fn($q) => $q->where('order_type', $request->order_type)
            )
            ->when(
                $request->status !== 'all' && $request->filled('status'),
                fn($q) => $q->where('status', $request->status)
            )
            ->first();

        $paymentBreakdown = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total) as total')
            ->groupBy('payment_method')
            ->get();

        $orderTypeBreakdown = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('order_type, COUNT(*) as count, SUM(total) as total')
            ->groupBy('order_type')
            ->get();

        $dailySales = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dayTotal = Sale::whereDate('created_at', $currentDate)->sum('total');
            $dailySales[] = [
                'date' => $currentDate->format('Y-m-d'),
                'label' => $currentDate->translatedFormat('d M'),
                'total' => (float) $dayTotal,
                'count' => Sale::whereDate('created_at', $currentDate)->count()
            ];
            $currentDate->addDay();
        }

        $topProducts = DB::table('sale_items')
            ->selectRaw('products.id, products.name, SUM(sale_items.qty) as total_qty, SUM(sale_items.qty * sale_items.price) as total_revenue')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->when(
                $request->payment_method !== 'all' && $request->filled('payment_method'),
                fn($q) => $q->where('sales.payment_method', $request->payment_method)
            )
            ->when(
                $request->order_type !== 'all' && $request->filled('order_type'),
                fn($q) => $q->where('sales.order_type', $request->order_type)
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        $cashiers = DB::table('users')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('dashboard.reports.sales', compact(
            'sales',
            'summary',
            'paymentBreakdown',
            'orderTypeBreakdown',
            'dailySales',
            'topProducts',
            'cashiers',
            'startDate',
            'endDate'
        ));
    }

    public function transactions(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::createFromFormat('Y-m-d', $request->start_date)
            : Carbon::now()->startOfDay();

        $endDate = $request->end_date
            ? Carbon::createFromFormat('Y-m-d', $request->end_date)
            : Carbon::now()->endOfDay();

        $query = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->with(['items.product', 'cashier']);

        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('min_amount')) {
            $query->where('total', '>=', (float) $request->min_amount);
        }
        if ($request->filled('max_amount')) {
            $query->where('total', '<=', (float) $request->max_amount);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_count,
                SUM(total) as total_amount,
                AVG(total) as avg_amount,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed_count,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as draft_count
            ', ['completed', 'draft'])
            ->when(
                $request->payment_method !== 'all' && $request->filled('payment_method'),
                fn($q) => $q->where('payment_method', $request->payment_method)
            )
            ->when(
                $request->status !== 'all' && $request->filled('status'),
                fn($q) => $q->where('status', $request->status)
            )
            ->first();

        return view('dashboard.reports.transactions', compact(
            'transactions',
            'stats',
            'startDate',
            'endDate'
        ));
    }

    public function dailySummary(Request $request)
    {
        $date = $request->date
            ? Carbon::createFromFormat('Y-m-d', $request->date)
            : Carbon::now();

        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        $sales = Sale::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->with(['items.product', 'cashier'])
            ->orderBy('created_at', 'desc')
            ->get();

        $paymentSummary = Sale::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total) as total, SUM(paid_amount) as paid')
            ->groupBy('payment_method')
            ->get();

        $orderTypeSummary = Sale::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->selectRaw('order_type, COUNT(*) as count, SUM(total) as total')
            ->groupBy('order_type')
            ->get();

        $cashierSummary = Sale::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->selectRaw('users.name, COUNT(sales.id) as count, SUM(sales.total) as total')
            ->join('users', 'sales.user_id', '=', 'users.id')
            ->groupBy('users.id', 'users.name')
            ->get();
        $topProducts = DB::table('sale_items')
            ->selectRaw('products.name, SUM(sale_items.qty) as qty, SUM(sale_items.qty * sale_items.price) as revenue')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.created_at', [$startOfDay, $endOfDay])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        $summary = Sale::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->selectRaw('
                COUNT(*) as total_transactions,
                SUM(total) as total_revenue,
                COUNT(DISTINCT user_id) as cashiers_count,
                SUM(CASE WHEN order_type = ? THEN 1 ELSE 0 END) as dine_in_count,
                SUM(CASE WHEN order_type = ? THEN 1 ELSE 0 END) as takeaway_count
            ', ['dine_in', 'take_away'])
            ->first();

        return view('dashboard.reports.daily-summary', compact(
            'date',
            'sales',
            'paymentSummary',
            'orderTypeSummary',
            'cashierSummary',
            'topProducts',
            'summary'
        ));
    }

    public function export(Request $request)
    {
        $format = $request->input('format', 'csv');

        $startDate = $request->start_date
            ? Carbon::createFromFormat('Y-m-d', $request->start_date)
            : Carbon::now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::createFromFormat('Y-m-d', $request->end_date)
            : Carbon::now();

        $query = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->with(['items.product', 'cashier']);

        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        $sales = $query->orderBy('created_at', 'desc')->get();

        if ($format === 'xlsx') {
            return $this->exportExcel($sales, $startDate, $endDate);
        }

        return $this->exportCsv($sales, $startDate, $endDate);
    }

    private function exportExcel($sales, $startDate, $endDate)
    {
        $filename = "laporan_penjualan_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}.xlsx";

        $tmpFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $zip = new \ZipArchive();
        $zip->open($tmpFile, \ZipArchive::OVERWRITE);

        $zip->addFromString('_rels/.rels', $this->getRelsXml());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->getWorkbookRelsXml());
        $zip->addFromString('xl/workbook.xml', $this->getWorkbookXml());
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->getSheetXml($sales));
        $zip->addFromString('xl/styles.xml', $this->getStylesXml());
        $zip->addFromString('[Content_Types].xml', $this->getContentTypesXml());

        $zip->close();

        return response()->download($tmpFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private function getRelsXml()
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' .
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>' .
            '</Relationships>';
    }

    private function getWorkbookRelsXml()
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' .
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>' .
            '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>' .
            '</Relationships>';
    }

    private function getWorkbookXml()
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">' .
            '<workbookPr/>' .
            '<sheets><sheet name="Penjualan" sheetId="1" r:id="rId2"/></sheets>' .
            '</workbook>';
    }

    private function getStylesXml()
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' .
            '<numFmts count="1"><numFmt numFmtId="164" formatCode="#,##0.00"/></numFmts>' .
            '<fonts count="2">' .
            '<font><sz val="11"/><color theme="1"/><name val="Calibri"/></font>' .
            '<font><b/><sz val="11"/><color rgb="FF000000"/><name val="Calibri"/></font>' .
            '</fonts>' .
            '<fills count="3">' .
            '<fill><patternFill patternType="none"/></fill>' .
            '<fill><patternFill patternType="gray125"/></fill>' .
            '<fill><patternFill patternType="solid"><fgColor rgb="FFC00000"/></patternFill></fill>' .
            '</fills>' .
            '<borders count="2">' .
            '<border><left/><right/><top/><bottom/><diagonal/></border>' .
            '<border><left style="thin"><color auto/></left><right style="thin"><color auto/></right><top style="thin"><color auto/></top><bottom style="thin"><color auto/></bottom></border>' .
            '</borders>' .
            '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>' .
            '<cellXfs count="5">' .
            '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0"/>' .
            '<xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0"/>' .
            '<xf numFmtId="164" fontId="0" fillId="0" borderId="1" xfId="0"/>' .
            '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>' .
            '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>' .
            '</cellXfs>' .
            '</styleSheet>';
    }

    private function getContentTypesXml()
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">' .
            '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>' .
            '<Default Extension="xml" ContentType="application/xml"/>' .
            '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>' .
            '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>' .
            '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>' .
            '</Types>';
    }

    private function getSheetXml($sales)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' .
            '<sheetData>';

        $xml .= '<row rId="1"><c r="A1" s="1"><v>No. Order</v></c><c r="B1" s="1"><v>Tanggal</v></c><c r="C1" s="1"><v>Kasir</v></c><c r="D1" s="1"><v>Tipe Order</v></c><c r="E1" s="1"><v>Metode Pembayaran</v></c><c r="F1" s="2"><v>Total</v></c><c r="G1" s="2"><v>Jumlah Bayar</v></c><c r="H1" s="2"><v>Kembalian</v></c><c r="I1" s="1"><v>Status</v></c></row>';

        $row = 2;
        foreach ($sales as $sale) {
            $col = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
            $data = [
                $sale->order_number,
                $sale->created_at->translatedFormat('d/m/Y H:i'),
                $sale->cashier->name,
                $sale->order_type === 'dine_in' ? 'Dine In' : 'Take Away',
                ucfirst($sale->payment_method),
                $sale->total,
                $sale->paid_amount ?? 0,
                $sale->change_amount ?? 0,
                ucfirst($sale->status)
            ];

            $rowXml = '<row rId="' . $row . '">';
            foreach ($data as $key => $value) {
                $style = in_array($key, [5, 6, 7]) ? ' s="2"' : '';
                $type = in_array($key, [5, 6, 7]) ? '<v>' . (float) $value . '</v>' : '<v>' . htmlspecialchars($value) . '</v>';
                $rowXml .= '<c r="' . $col[$key] . $row . '"' . $style . '>' . $type . '</c>';
            }
            $rowXml .= '</row>';
            $xml .= $rowXml;
            $row++;
        }

        $xml .= '</sheetData></worksheet>';

        return $xml;
    }

    private function exportCsv($sales, $startDate, $endDate)
    {
        $filename = "laporan_penjualan_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($sales) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'No. Order',
                'Tanggal',
                'Kasir',
                'Tipe Order',
                'Metode Pembayaran',
                'Total',
                'Jumlah Bayar',
                'Kembalian',
                'Status'
            ], ',');

            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->order_number,
                    $sale->created_at->translatedFormat('d/m/Y H:i'),
                    $sale->cashier->name,
                    $sale->order_type === 'dine_in' ? 'Dine In' : 'Take Away',
                    ucfirst($sale->payment_method),
                    $sale->total,
                    $sale->paid_amount ?? 0,
                    $sale->change_amount ?? 0,
                    ucfirst($sale->status)
                ], ',');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
