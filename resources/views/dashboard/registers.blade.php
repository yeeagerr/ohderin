@extends('layouts.dashboard_layout')

@section('title', 'Manajemen Kasir/Register')

@section('content')
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50">
        <div class="bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 mb-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-gray-800 tracking-tight">MANAJEMEN KASIR</h1>
                    <p class="text-sm text-gray-500">Kelola POS/register, buka session, dan lihat log session.</p>
                </div>
                <button onclick="document.getElementById('createRegisterModal').classList.remove('hidden')"
                    class="px-4 py-2.5 bg-orange-500 text-white rounded-xl hover:bg-orange-600 font-semibold text-sm">
                    + Tambah Kasir
                </button>
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

        <div class="bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 mt-5">
            <h2 class="text-lg font-bold text-gray-900 mb-3">Log Session POS</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-200">
                            <th class="py-2">POS</th>
                            <th class="py-2">Buka</th>
                            <th class="py-2">Tutup</th>
                            <th class="py-2">Uang Awal</th>
                            <th class="py-2">Uang Akhir</th>
                            <th class="py-2">Transaksi</th>
                            <th class="py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessionLogs as $log)
                            <tr class="border-b border-gray-100 text-gray-700">
                                <td class="py-2 font-medium">{{ $log->register->name ?? '-' }}</td>
                                <td class="py-2">{{ optional($log->opened_at)->format('d M Y H:i') }}</td>
                                <td class="py-2">{{ optional($log->closed_at)->format('d M Y H:i') ?? '-' }}</td>
                                <td class="py-2">Rp {{ number_format($log->opening_cash, 0, ',', '.') }}</td>
                                <td class="py-2">{{ $log->closing_cash !== null ? 'Rp ' . number_format($log->closing_cash, 0, ',', '.') : '-' }}</td>
                                <td class="py-2">{{ $log->total_transactions }}</td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $log->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ strtoupper($log->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-gray-400">Belum ada log session.</td>
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
        <div class="bg-white rounded-2xl w-full max-w-md p-6 space-y-4">
            <h2 class="text-xl font-bold text-gray-900">Open Session POS</h2>
            <p id="openSessionRegisterName" class="text-sm text-gray-600">POS: -</p>
            <input type="hidden" id="openSessionRegisterId" />
            <div>
                <label class="text-sm text-gray-700">Uang Awal</label>
                <input id="openSessionCashInput" type="number" min="0" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-xl" />
            </div>
            <div>
                <label class="text-sm text-gray-700">Catatan</label>
                <textarea id="openSessionNoteInput" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-xl"></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('openSessionModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-xl">Batal</button>
                <button type="button" onclick="submitOpenSession()" class="px-4 py-2 bg-orange-500 text-white rounded-xl">Open & Masuk POS</button>
            </div>
        </div>
    </div>

    <div id="closeSessionModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 space-y-4">
            <h2 class="text-xl font-bold text-gray-900">Close Session POS</h2>
            <p id="closeSessionRegisterName" class="text-sm text-gray-600">POS: -</p>
            <input type="hidden" id="closeSessionId" />
            <div>
                <label class="text-sm text-gray-700">Uang Akhir</label>
                <input id="closeSessionCashInput" type="number" min="0" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-xl" />
            </div>
            <div>
                <label class="text-sm text-gray-700">Catatan</label>
                <textarea id="closeSessionNoteInput" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-xl"></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('closeSessionModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-xl">Batal</button>
                <button type="button" onclick="submitCloseSession()" class="px-4 py-2 bg-gray-700 text-white rounded-xl">Close Session</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        function showEditRegister(id, name, isActive) {
            const form = document.getElementById('editRegisterForm');
            form.action = `/kasir/registers/${id}`;
            document.getElementById('editRegisterName').value = name;
            document.getElementById('editRegisterActive').checked = !!isActive;
            document.getElementById('editRegisterModal').classList.remove('hidden');
        }

        function openSessionFlow(registerId, registerName) {
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

            fetch(`/kasir/registers/${registerId}/open`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ opening_cash: openingCash, opening_note: openingNote }),
            })
            .then(r => r.json())
            .then((data) => {
                if (!data.success) return alert(data.message || 'Gagal open session');
                window.location.href = data.redirect_url || `/kasir/pos?pos_id=${registerId}`;
            })
            .catch(() => alert('Gagal open session'));
        }

        function showCloseSessionModal(sessionId, registerName) {
            document.getElementById('closeSessionId').value = sessionId;
            document.getElementById('closeSessionRegisterName').textContent = `POS: ${registerName}`;
            document.getElementById('closeSessionModal').classList.remove('hidden');
        }

        function submitCloseSession() {
            const sessionId = document.getElementById('closeSessionId').value;
            const closingCash = parseFloat(document.getElementById('closeSessionCashInput').value || 0);
            const closingNote = document.getElementById('closeSessionNoteInput').value || '';

            fetch(`/kasir/register-sessions/${sessionId}/close`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ closing_cash: closingCash, closing_note: closingNote }),
            })
            .then(r => r.json())
            .then((data) => {
                if (!data.success) return alert(data.message || 'Gagal close session');
                window.location.reload();
            })
            .catch(() => alert('Gagal close session'));
        }
    </script>
@endsection
