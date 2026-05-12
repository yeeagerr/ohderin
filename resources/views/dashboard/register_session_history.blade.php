@extends('layouts.dashboard_layout')

@section('title', 'History Session Kasir')

@section('content')
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
        <div class="bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 mb-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-800 tracking-tight">HISTORY SESSION KASIR</h1>
                    <p class="text-sm text-gray-500">Riwayat buka tutup kasir, omzet session, dan selisih cash.</p>
                </div>
                <a href="{{ route('kasir.registers.index') }}"
                    class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold text-sm text-center">
                    Kembali ke Kasir
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div class="bg-white border border-gray-200 rounded-2xl p-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Session</p>
                <p class="text-2xl font-black text-gray-800 mt-2">{{ number_format($summary['total_sessions'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl p-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sedang Open</p>
                <p class="text-2xl font-black text-green-600 mt-2">{{ number_format($summary['open_sessions'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl p-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sudah Closed</p>
                <p class="text-2xl font-black text-gray-800 mt-2">{{ number_format($summary['closed_sessions'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl p-4">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Penjualan</p>
                <p class="text-2xl font-black text-orange-600 mt-2">Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 mb-4">
            <form method="GET" action="{{ route('kasir.registers.history') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="text-xs font-semibold text-gray-500">Kasir</label>
                    <select name="register_id" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-xl text-sm">
                        <option value="">Semua kasir</option>
                        @foreach($registers as $register)
                            <option value="{{ $register->id }}" {{ (string) request('register_id') === (string) $register->id ? 'selected' : '' }}>
                                {{ $register->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500">Status</label>
                    <select name="status" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-xl text-sm">
                        <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Semua status</option>
                        <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-orange-500 text-white rounded-xl text-sm font-semibold hover:bg-orange-600">
                        Filter
                    </button>
                    <a href="{{ route('kasir.registers.history') }}" class="px-4 py-2 border border-gray-300 rounded-xl text-sm text-gray-700 hover:bg-gray-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-4 sm:p-5">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-200">
                            <th class="py-3 pr-4">Kasir</th>
                            <th class="py-3 pr-4">Buka</th>
                            <th class="py-3 pr-4">Tutup</th>
                            <th class="py-3 pr-4">Modal Awal</th>
                            <th class="py-3 pr-4">Cash Seharusnya</th>
                            <th class="py-3 pr-4">Uang Akhir</th>
                            <th class="py-3 pr-4">Selisih</th>
                            <th class="py-3 pr-4">Transaksi</th>
                            <th class="py-3 pr-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessionLogs as $log)
                            @php
                                $expectedCash = data_get($log->session_summary, 'expected_cash');
                                $difference = $log->cash_difference;
                            @endphp
                            <tr class="border-b border-gray-100 text-gray-700 align-top">
                                <td class="py-3 pr-4 font-medium">{{ $log->register->name ?? '-' }}</td>
                                <td class="py-3 pr-4">
                                    <div>{{ optional($log->opened_at)->format('d M Y H:i') }}</div>
                                    <div class="text-xs text-gray-400">{{ $log->openedBy->name ?? '-' }}</div>
                                </td>
                                <td class="py-3 pr-4">
                                    <div>{{ optional($log->closed_at)->format('d M Y H:i') ?? '-' }}</div>
                                    <div class="text-xs text-gray-400">{{ $log->closedBy->name ?? '-' }}</div>
                                </td>
                                <td class="py-3 pr-4">Rp {{ number_format($log->opening_cash, 0, ',', '.') }}</td>
                                <td class="py-3 pr-4">{{ $expectedCash !== null ? 'Rp ' . number_format($expectedCash, 0, ',', '.') : '-' }}</td>
                                <td class="py-3 pr-4">{{ $log->closing_cash !== null ? 'Rp ' . number_format($log->closing_cash, 0, ',', '.') : '-' }}</td>
                                <td class="py-3 pr-4">
                                    @if($difference === null)
                                        -
                                    @else
                                        <span class="{{ $difference < 0 ? 'text-red-600' : ($difference > 0 ? 'text-green-600' : 'text-gray-700') }} font-semibold">
                                            {{ $difference < 0 ? '-' : '' }}Rp {{ number_format(abs($difference), 0, ',', '.') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 pr-4">
                                    <div class="font-semibold">{{ $log->total_transactions }}</div>
                                    <div class="text-xs text-gray-400">Rp {{ number_format($log->total_sales, 0, ',', '.') }}</div>
                                </td>
                                <td class="py-3 pr-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $log->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ strtoupper($log->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-8 text-center text-gray-400">Belum ada history session.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $sessionLogs->links() }}
            </div>
        </div>
    </main>
@endsection
