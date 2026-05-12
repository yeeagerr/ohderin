<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Register;
use App\Models\RegisterSession;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function index()
    {
        $registers = Register::with(['activeSession'])->orderBy('name')->get();
        $activeSession = $this->getActiveSession();

        return view('dashboard.registers', compact('registers', 'activeSession'));
    }

    public function history(Request $request)
    {
        $registers = Register::orderBy('name')->get();

        $sessionLogs = RegisterSession::with(['register', 'openedBy', 'closedBy'])
            ->when($request->filled('register_id'), fn ($query) => $query->where('register_id', $request->register_id))
            ->when($request->filled('status') && $request->status !== 'all', fn ($query) => $query->where('status', $request->status))
            ->latest('opened_at')
            ->paginate(15)
            ->withQueryString();

        $summaryQuery = RegisterSession::query()
            ->when($request->filled('register_id'), fn ($query) => $query->where('register_id', $request->register_id))
            ->when($request->filled('status') && $request->status !== 'all', fn ($query) => $query->where('status', $request->status));

        $summary = [
            'total_sessions' => (clone $summaryQuery)->count(),
            'open_sessions' => (clone $summaryQuery)->where('status', 'open')->count(),
            'closed_sessions' => (clone $summaryQuery)->where('status', 'closed')->count(),
            'total_sales' => (float) (clone $summaryQuery)->sum('total_sales'),
        ];

        return view('dashboard.register_session_history', compact('registers', 'sessionLogs', 'summary'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:registers,name',
            'is_active' => 'nullable|boolean',
        ]);

        Register::create([
            'name' => $data['name'],
            'is_active' => (bool) ($data['is_active'] ?? true),
        ]);

        return back()->with('success', 'Kasir berhasil dibuat.');
    }

    public function update(Request $request, Register $register)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:registers,name,' . $register->id,
            'is_active' => 'required|boolean',
        ]);

        $register->update($data);

        return back()->with('success', 'Kasir berhasil diperbarui.');
    }

    public function destroy(Register $register)
    {
        if ($register->sessions()->exists()) {
            return back()->with('error', 'Kasir tidak bisa dihapus karena sudah memiliki riwayat session.');
        }

        $register->delete();

        return back()->with('success', 'Kasir berhasil dihapus.');
    }

    public function enter(Register $register)
    {
        if (!$register->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Kasir nonaktif tidak bisa digunakan.',
            ], 422);
        }

        $openSession = $register->sessions()->where('status', 'open')->latest()->first();
        if ($openSession) {
            session(['active_register_session_id' => $openSession->id]);
            return response()->json([
                'success' => true,
                'needs_open_register' => false,
                'message' => 'Session kasir aktif.',
                'redirect_url' => route('kasir.pos', ['pos_id' => $register->id]),
            ]);
        }

        return response()->json([
            'success' => true,
            'needs_open_register' => true,
            'message' => 'Kasir belum dibuka. Silakan isi Open Register.',
            'redirect_url' => route('kasir.pos', ['pos_id' => $register->id]),
        ]);
    }

    public function open(Request $request, Register $register)
    {
        $data = $request->validate([
            'opening_cash' => 'required|numeric|min:0',
            'opening_note' => 'nullable|string|max:500',
        ]);

        if (!$register->is_active) {
            return response()->json(['success' => false, 'message' => 'Kasir nonaktif.'], 422);
        }

        if ($register->sessions()->where('status', 'open')->exists()) {
            return response()->json(['success' => false, 'message' => 'Kasir ini sudah dalam kondisi open.'], 422);
        }

        $session = RegisterSession::create([
            'register_id' => $register->id,
            'opened_by' => Auth::id(),
            'opened_at' => now(),
            'opening_cash' => $data['opening_cash'],
            'opening_note' => $data['opening_note'] ?? null,
            'status' => 'open',
        ]);

        session(['active_register_session_id' => $session->id]);

        return response()->json([
            'success' => true,
            'message' => 'Register berhasil dibuka.',
            'redirect_url' => route('kasir.pos', ['pos_id' => $register->id]),
        ]);
    }

    public function close(Request $request, RegisterSession $registerSession)
    {
        $data = $request->validate([
            'closing_cash' => 'required|numeric|min:0',
            'closing_note' => 'nullable|string|max:500',
        ]);

        if ($registerSession->status !== 'open') {
            return response()->json(['success' => false, 'message' => 'Session register sudah ditutup.'], 422);
        }

        DB::transaction(function () use ($registerSession, $data) {
            $salesQuery = Sale::where('register_session_id', $registerSession->id)
                ->where('status', 'completed');

            $totalTransactions = (clone $salesQuery)->count();
            $totalSales = (float) (clone $salesQuery)->sum('total');
            $cashSales = (float) (clone $salesQuery)->where('payment_method', 'cash')->sum('total');
            $nonCashSales = $totalSales - $cashSales;
            $draftTransactions = Sale::where('register_session_id', $registerSession->id)
                ->where('status', 'draft')
                ->count();
            $expectedCash = (float) $registerSession->opening_cash + $cashSales;
            $closingCash = (float) $data['closing_cash'];
            $cashDifference = $closingCash - $expectedCash;

            $registerSession->update([
                'closed_by' => Auth::id(),
                'closed_at' => now(),
                'closing_cash' => $closingCash,
                'total_transactions' => $totalTransactions,
                'total_sales' => $totalSales,
                'cash_difference' => $cashDifference,
                'status' => 'closed',
                'session_summary' => [
                    'opening_cash' => (float) $registerSession->opening_cash,
                    'cash_sales' => $cashSales,
                    'non_cash_sales' => $nonCashSales,
                    'expected_cash' => $expectedCash,
                    'draft_transactions' => $draftTransactions,
                    'closing_note' => $data['closing_note'] ?? null,
                ],
            ]);
        });

        if ((int) session('active_register_session_id') === (int) $registerSession->id) {
            session()->forget('active_register_session_id');
        }

        return response()->json([
            'success' => true,
            'message' => 'Register berhasil ditutup.',
            'summary' => [
                'closed_at' => optional($registerSession->fresh()->closed_at)->format('d M Y H:i'),
                'total_transactions' => $registerSession->fresh()->total_transactions,
                'total_sales' => $registerSession->fresh()->total_sales,
                'cash_difference' => $registerSession->fresh()->cash_difference,
                'expected_cash' => $registerSession->fresh()->session_summary['expected_cash'] ?? null,
            ],
        ]);
    }

    public function summary(RegisterSession $registerSession)
    {
        if ($registerSession->status !== 'open') {
            return response()->json([
                'success' => false,
                'message' => 'Session register sudah ditutup.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'summary' => $this->buildOpenSessionSummary($registerSession),
        ]);
    }

    public function status()
    {
        $activeSession = $this->getActiveSession();

        return response()->json([
            'active_session' => $activeSession ? [
                'id' => $activeSession->id,
                'register_name' => $activeSession->register->name,
                'opened_at' => optional($activeSession->opened_at)->format('d M Y H:i'),
                'opening_cash' => (float) $activeSession->opening_cash,
                'status' => $activeSession->status,
            ] : null,
        ]);
    }

    public static function getActiveSession(): ?RegisterSession
    {
        $sessionId = session('active_register_session_id');
        if (!$sessionId) {
            return null;
        }

        return RegisterSession::with('register')
            ->where('id', $sessionId)
            ->where('status', 'open')
            ->first();
    }

    private function buildOpenSessionSummary(RegisterSession $registerSession): array
    {
        $salesQuery = Sale::where('register_session_id', $registerSession->id)
            ->where('status', 'completed');

        $totalTransactions = (clone $salesQuery)->count();
        $totalSales = (float) (clone $salesQuery)->sum('total');
        $cashSales = (float) (clone $salesQuery)->where('payment_method', 'cash')->sum('total');
        $nonCashSales = $totalSales - $cashSales;
        $draftTransactions = Sale::where('register_session_id', $registerSession->id)
            ->where('status', 'draft')
            ->count();
        $openingCash = (float) $registerSession->opening_cash;

        return [
            'register_name' => $registerSession->register->name ?? '-',
            'opened_at' => optional($registerSession->opened_at)->format('d M Y H:i'),
            'opening_cash' => $openingCash,
            'total_transactions' => $totalTransactions,
            'draft_transactions' => $draftTransactions,
            'total_sales' => $totalSales,
            'cash_sales' => $cashSales,
            'non_cash_sales' => $nonCashSales,
            'expected_cash' => $openingCash + $cashSales,
        ];
    }
}
