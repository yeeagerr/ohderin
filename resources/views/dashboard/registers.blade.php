@extends('layouts.dashboard_layout')

@section('title', 'Manajemen Kasir/Register')

@section('content')
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
        <div class="bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 mb-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-800 tracking-tight">MANAJEMEN KASIR</h1>
                    <p class="text-sm text-gray-500">Kelola POS/register dan buka atau tutup session kasir.</p>
                </div>
                <div class="flex flex-wrap justify-end gap-2">
                    <a href="{{ route('kasir.registers.history') }}"
                        class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-semibold text-sm">
                        History Session
                    </a>
                    <button onclick="document.getElementById('createRegisterModal').classList.remove('hidden')"
                        class="px-4 py-2.5 bg-orange-500 text-white rounded-xl hover:bg-orange-600 font-semibold text-sm">
                        + Tambah Kasir
                    </button>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-xl text-sm">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-xl text-sm">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($registers as $register)
                @php
                    $active = $activeSession && $activeSession->register_id === $register->id;
                @endphp
                <div class="bg-white rounded-2xl border {{ $active ? 'border-orange-400 ring-1 ring-orange-200' : 'border-gray-200' }} p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $register->name }}</h3>
                            <p class="text-sm {{ $register->is_active ? 'text-green-600' : 'text-red-500' }}">
                                {{ $register->is_active ? 'Aktif' : 'Nonaktif' }}
                            </p>
                        </div>
                        @if($active)
                            <span class="px-2 py-1 text-xs bg-orange-100 text-orange-700 rounded-full font-medium">Session Aktif</span>
                        @endif
                    </div>

                    <div class="text-sm text-gray-600 space-y-1">
                        <p>Status Register: {{ $register->activeSession ? 'Open' : 'Closed' }}</p>
                        @if($register->activeSession)
                            <p>Dibuka: {{ optional($register->activeSession->opened_at)->format('d M Y H:i') }}</p>
                            <p>Modal Awal: Rp {{ number_format($register->activeSession->opening_cash, 0, ',', '.') }}</p>
                        @endif
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <button onclick='openSessionFlow({{ $register->id }}, @json($register->name))'
                            class="px-3 py-2 bg-orange-500 text-white rounded-lg text-sm hover:bg-orange-600">
                            Open Session
                        </button>
                        @if($register->activeSession)
                            <button onclick='showCloseSessionModal({{ $register->activeSession->id }}, @json($register->name))'
                                class="px-3 py-2 bg-gray-700 text-white rounded-lg text-sm hover:bg-gray-800">
                                Close Session
                            </button>
                        @endif
                        <button onclick='showEditRegister({{ $register->id }}, @json($register->name), {{ $register->is_active ? 'true' : 'false' }})'
                            class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                            Edit
                        </button>
                        <form action="{{ route('kasir.registers.delete', $register) }}" method="POST" onsubmit="return confirm('Hapus kasir ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 border border-red-200 text-red-600 rounded-lg text-sm hover:bg-red-50">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

    </main>

    <div id="createRegisterModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <form action="{{ route('kasir.registers.store') }}" method="POST" class="bg-white rounded-2xl w-full max-w-md p-6 space-y-4">
            @csrf
            <h2 class="text-xl font-bold text-gray-900">Tambah Kasir</h2>
            <div>
                <label class="text-sm text-gray-700">Nama Kasir</label>
                <input name="name" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-xl" required />
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_active" value="1" checked /> Aktif
            </label>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('createRegisterModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-xl">Simpan</button>
            </div>
        </form>
    </div>

    <div id="editRegisterModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <form id="editRegisterForm" method="POST" class="bg-white rounded-2xl w-full max-w-md p-6 space-y-4">
            @csrf
            @method('PUT')
            <h2 class="text-xl font-bold text-gray-900">Edit Kasir</h2>
            <div>
                <label class="text-sm text-gray-700">Nama Kasir</label>
                <input name="name" id="editRegisterName" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-xl" required />
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="hidden" name="is_active" value="0" />
                <input type="checkbox" name="is_active" id="editRegisterActive" value="1" /> Aktif
            </label>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('editRegisterModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-xl">Update</button>
            </div>
        </form>
    </div>

    <div id="openSessionModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 space-y-5">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Buka Session Kasir</h2>
                <p id="openSessionRegisterName" class="text-sm text-gray-600 mt-1">POS: -</p>
            </div>
            <input type="hidden" id="openSessionRegisterId" />
            <div class="rounded-xl bg-orange-50 border border-orange-100 p-3 text-sm text-orange-800">
                Masukkan modal awal sesuai uang cash fisik di laci sebelum transaksi pertama.
            </div>
            <div>
                <label class="text-sm text-gray-700">Uang Awal</label>
                <input id="openSessionCashInput" type="number" min="0" step="1000" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-xl" placeholder="0" />
            </div>
            <div>
                <label class="text-sm text-gray-700">Catatan</label>
                <textarea id="openSessionNoteInput" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-xl" rows="3" placeholder="Opsional"></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('openSessionModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-xl">Batal</button>
                <button type="button" id="openSessionSubmitBtn" onclick="submitOpenSession()" class="px-4 py-2 bg-orange-500 text-white rounded-xl">Buka & Masuk POS</button>
            </div>
        </div>
    </div>

    <div id="closeSessionModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg p-6 space-y-5">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Tutup Session Kasir</h2>
                <p id="closeSessionRegisterName" class="text-sm text-gray-600 mt-1">POS: -</p>
            </div>
            <input type="hidden" id="closeSessionId" />
            <div id="closeSessionSummary" class="grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-xl bg-gray-50 p-3">
                    <p class="text-gray-500">Modal Awal</p>
                    <p id="closeSummaryOpeningCash" class="font-bold text-gray-900">Rp 0</p>
                </div>
                <div class="rounded-xl bg-gray-50 p-3">
                    <p class="text-gray-500">Penjualan Cash</p>
                    <p id="closeSummaryCashSales" class="font-bold text-gray-900">Rp 0</p>
                </div>
                <div class="rounded-xl bg-gray-50 p-3">
                    <p class="text-gray-500">Non-Cash</p>
                    <p id="closeSummaryNonCashSales" class="font-bold text-gray-900">Rp 0</p>
                </div>
                <div class="rounded-xl bg-orange-50 p-3">
                    <p class="text-orange-700">Cash Seharusnya</p>
                    <p id="closeSummaryExpectedCash" class="font-bold text-orange-700">Rp 0</p>
                </div>
            </div>
            <div>
                <label class="text-sm text-gray-700">Uang Akhir</label>
                <input id="closeSessionCashInput" type="number" min="0" step="1000" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-xl" placeholder="Hitung uang cash fisik" />
                <p id="closeSessionDifference" class="text-xs text-gray-500 mt-1">Selisih akan dihitung otomatis.</p>
            </div>
            <div>
                <label class="text-sm text-gray-700">Catatan</label>
                <textarea id="closeSessionNoteInput" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-xl" rows="3" placeholder="Opsional, misalnya alasan selisih"></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('closeSessionModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-xl">Batal</button>
                <button type="button" id="closeSessionSubmitBtn" onclick="submitCloseSession()" class="px-4 py-2 bg-gray-700 text-white rounded-xl">Tutup Session</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        let closeExpectedCash = 0;

        function formatRupiah(value) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(value || 0));
        }

        function setButtonLoading(button, isLoading, loadingText, normalText) {
            if (!button) return;
            button.disabled = isLoading;
            button.textContent = isLoading ? loadingText : normalText;
            button.classList.toggle('opacity-70', isLoading);
            button.classList.toggle('cursor-not-allowed', isLoading);
        }

        function updateCloseDifference() {
            const input = document.getElementById('closeSessionCashInput');
            const label = document.getElementById('closeSessionDifference');
            if (!input || !label) return;

            const closingCash = parseFloat(input.value || 0);
            const difference = closingCash - closeExpectedCash;
            const isOver = difference > 0;
            const isShort = difference < 0;

            label.textContent = `Selisih: ${formatRupiah(Math.abs(difference))}${isOver ? ' lebih' : isShort ? ' kurang' : ''}`;
            label.className = `text-xs mt-1 ${isOver ? 'text-green-600' : isShort ? 'text-red-600' : 'text-gray-500'}`;
        }

        document.getElementById('closeSessionCashInput')?.addEventListener('input', updateCloseDifference);

        function showEditRegister(id, name, isActive) {
            const form = document.getElementById('editRegisterForm');
            form.action = `/kasir/registers/${id}`;
            document.getElementById('editRegisterName').value = name;
            document.getElementById('editRegisterActive').checked = !!isActive;
            document.getElementById('editRegisterModal').classList.remove('hidden');
        }

        function openSessionFlow(registerId, registerName) {
            document.getElementById('openSessionCashInput').value = '';
            document.getElementById('openSessionNoteInput').value = '';

            fetch(`/kasir/registers/${registerId}/enter`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            })
            .then(r => r.json())
            .then((data) => {
                if (!data.success) return alert(data.message || 'Gagal masuk session');
                if (data.needs_open_register) {
                    document.getElementById('openSessionRegisterId').value = registerId;
                    document.getElementById('openSessionRegisterName').textContent = `POS: ${registerName}`;
                    document.getElementById('openSessionModal').classList.remove('hidden');
                    return;
                }
                window.location.href = data.redirect_url || `/kasir/pos?pos_id=${registerId}`;
            })
            .catch(() => alert('Gagal masuk session'));
        }

        function submitOpenSession() {
            const registerId = document.getElementById('openSessionRegisterId').value;
            const openingCash = parseFloat(document.getElementById('openSessionCashInput').value || 0);
            const openingNote = document.getElementById('openSessionNoteInput').value || '';
            const button = document.getElementById('openSessionSubmitBtn');

            setButtonLoading(button, true, 'Membuka...', 'Buka & Masuk POS');

            fetch(`/kasir/registers/${registerId}/open`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ opening_cash: openingCash, opening_note: openingNote }),
            })
            .then(r => r.json())
            .then((data) => {
                if (!data.success) {
                    setButtonLoading(button, false, 'Membuka...', 'Buka & Masuk POS');
                    return alert(data.message || 'Gagal open session');
                }
                window.location.href = data.redirect_url || `/kasir/pos?pos_id=${registerId}`;
            })
            .catch(() => {
                setButtonLoading(button, false, 'Membuka...', 'Buka & Masuk POS');
                alert('Gagal open session');
            });
        }

        function showCloseSessionModal(sessionId, registerName) {
            document.getElementById('closeSessionId').value = sessionId;
            document.getElementById('closeSessionRegisterName').textContent = `POS: ${registerName}`;
            document.getElementById('closeSessionCashInput').value = '';
            document.getElementById('closeSessionNoteInput').value = '';
            document.getElementById('closeSummaryOpeningCash').textContent = 'Memuat...';
            document.getElementById('closeSummaryCashSales').textContent = 'Memuat...';
            document.getElementById('closeSummaryNonCashSales').textContent = 'Memuat...';
            document.getElementById('closeSummaryExpectedCash').textContent = 'Memuat...';
            document.getElementById('closeSessionDifference').textContent = 'Selisih akan dihitung otomatis.';
            document.getElementById('closeSessionDifference').className = 'text-xs text-gray-500 mt-1';
            document.getElementById('closeSessionModal').classList.remove('hidden');

            fetch(`/kasir/register-sessions/${sessionId}/summary`)
                .then(r => r.json())
                .then((data) => {
                    if (!data.success) return alert(data.message || 'Gagal memuat ringkasan session');
                    const summary = data.summary || {};
                    closeExpectedCash = Number(summary.expected_cash || 0);
                    document.getElementById('closeSummaryOpeningCash').textContent = formatRupiah(summary.opening_cash);
                    document.getElementById('closeSummaryCashSales').textContent = formatRupiah(summary.cash_sales);
                    document.getElementById('closeSummaryNonCashSales').textContent = formatRupiah(summary.non_cash_sales);
                    document.getElementById('closeSummaryExpectedCash').textContent = formatRupiah(summary.expected_cash);
                    document.getElementById('closeSessionCashInput').value = Math.round(closeExpectedCash);
                    updateCloseDifference();
                })
                .catch(() => alert('Gagal memuat ringkasan session'));
        }

        function submitCloseSession() {
            const sessionId = document.getElementById('closeSessionId').value;
            const closingCash = parseFloat(document.getElementById('closeSessionCashInput').value || 0);
            const closingNote = document.getElementById('closeSessionNoteInput').value || '';
            const button = document.getElementById('closeSessionSubmitBtn');
            const difference = closingCash - closeExpectedCash;
            const confirmMessage = difference === 0
                ? 'Tutup session kasir ini?'
                : `Tutup session dengan selisih ${formatRupiah(Math.abs(difference))}${difference > 0 ? ' lebih' : ' kurang'}?`;

            if (!confirm(confirmMessage)) return;

            setButtonLoading(button, true, 'Menutup...', 'Tutup Session');

            fetch(`/kasir/register-sessions/${sessionId}/close`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ closing_cash: closingCash, closing_note: closingNote }),
            })
            .then(r => r.json())
            .then((data) => {
                if (!data.success) {
                    setButtonLoading(button, false, 'Menutup...', 'Tutup Session');
                    return alert(data.message || 'Gagal close session');
                }
                window.location.reload();
            })
            .catch(() => {
                setButtonLoading(button, false, 'Menutup...', 'Tutup Session');
                alert('Gagal close session');
            });
        }
    </script>
@endsection
