@extends('layouts.dashboard_layout')

@section('title', 'Manajemen Role')

@section('content')
<div class="flex-1 overflow-auto bg-gray-50/50">
    <div class="p-6">
        <div class="flex justify-between items-end mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Role</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola peran dan hak akses menu untuk pengguna.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-xl transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke User
                </a>
                <a href="{{ route('roles.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-xl transition-colors shadow-sm shadow-orange-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Role
                </a>
            </div>
        </div>

        @if (session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
        @endif

        @if (session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative flex items-center gap-3">
            <span class="block sm:inline font-medium">{{ session('error') }}</span>
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-900">Nama Role</th>
                            <th class="px-6 py-4 font-semibold text-gray-900">Jumlah Akses</th>
                            <th class="px-6 py-4 font-semibold text-gray-900 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($roles as $role)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-gray-700 font-medium">
                                {{ $role->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                @if($role->name === 'Super Admin')
                                    <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-xs font-medium border border-purple-100">All Access</span>
                                @else
                                    {{ is_array($role->permissions) ? count($role->permissions) : 0 }} Menu
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('roles.edit', $role) }}" class="text-indigo-600 hover:text-indigo-900 mx-2 text-sm font-medium">Edit</a>
                                @if($role->name !== 'Super Admin')
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus role ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 mx-2 text-sm font-medium">Hapus</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
