<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $todayRevenue = Sale::completed()->whereDate('created_at', $today)->sum('total');

        $yesterdayRevenue = Sale::completed()->whereDate('created_at', Carbon::yesterday())->sum('total');
        $revenueChange = $yesterdayRevenue > 0
            ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1)
            : ($todayRevenue > 0 ? 100 : 0);

        $todayTransactions = Sale::completed()->whereDate('created_at', $today)->count();
        $yesterdayTransactions = Sale::completed()->whereDate('created_at', Carbon::yesterday())->count();
        $transactionChange = $yesterdayTransactions > 0
            ? round((($todayTransactions - $yesterdayTransactions) / $yesterdayTransactions) * 100, 1)
            : ($todayTransactions > 0 ? 100 : 0);

        // Produk Terjual Hari Ini
        $todayItemsSold = SaleItem::whereHas('sale', function ($q) use ($today) {
            $q->completed()->whereDate('created_at', $today);
        })->sum('qty');
        $yesterdayItemsSold = SaleItem::whereHas('sale', function ($q) {
            $q->completed()->whereDate('created_at', Carbon::yesterday());
        })->sum('qty');
        $itemsSoldChange = $yesterdayItemsSold > 0
            ? round((($todayItemsSold - $yesterdayItemsSold) / $yesterdayItemsSold) * 100, 1)
            : ($todayItemsSold > 0 ? 100 : 0);

        // Total refund. If refund columns are not migrated yet, keep dashboard stable at 0.
        $totalRefund = $this->sumRefunds();

        $monthRevenue = Sale::completed()->where('created_at', '>=', $startOfMonth)->sum('total');
        $monthTransactions = Sale::completed()->where('created_at', '>=', $startOfMonth)->count();
        $lastMonthRevenue = Sale::completed()->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('total');
        $monthRevenueChange = $lastMonthRevenue > 0
            ? round((($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : ($monthRevenue > 0 ? 100 : 0);

        $recentSales = Sale::with('cashier')
            ->completed()
            ->withCount('items')
            ->latest()
            ->take(5)
            ->get();

        // PRODUK TERJUAL HARI INI (total)
        $totalItemsSold = $todayItemsSold;

        $topProducts = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereDate('sales.created_at', $today)
            ->where('sales.status', 'completed')
            ->select('products.name', DB::raw('SUM(sale_items.qty) as total_qty'))
            ->groupBy('products.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Calculate max for progress bar percentage
        $maxQty = $topProducts->isNotEmpty() ? $topProducts->first()->total_qty : 1;

        // 7 hari terakhir
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->translatedFormat('D');
            $chartData[] = (float) Sale::completed()->whereDate('created_at', $date)->sum('total');
        }

        return view('dashboard.index', compact(
            'todayRevenue',
            'revenueChange',
            'todayTransactions',
            'transactionChange',
            'todayItemsSold',
            'itemsSoldChange',
            'totalRefund',
            'monthRevenue',
            'monthTransactions',
            'monthRevenueChange',
            'recentSales',
            'totalItemsSold',
            'topProducts',
            'maxQty',
            'chartLabels',
            'chartData'
        ));
    }

    private function sumRefunds(): float
    {
        if (!Schema::hasColumn('sales', 'refund_amount')) {
            return 0;
        }

        return (float) Sale::where('refund_amount', '>', 0)->sum('refund_amount');
    }
}
