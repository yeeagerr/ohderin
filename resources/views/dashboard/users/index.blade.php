@extends('layouts.dashboard_layout')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="flex-1 overflow-auto bg-gray-50/50">
    <div class="p-6">
        <div class="flex justify-between items-end mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Pengguna</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola daftar pengguna (User Invited) dan hak akses mereka.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('roles.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-xl transition-colors shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Kelola Role
                </a>
                <a href="{{ route('users.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-xl transition-colors shadow-sm shadow-orange-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Undang User
                </a>
            </div>
        </div>

        @if (session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-900">Email</th>
                            <th class="px-6 py-4 font-semibold text-gray-900">Role</th>
                            <th class="px-6 py-4 font-semibold text-gray-900 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-gray-700 font-medium">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-medium border border-blue-100">
                                    {{ $user->role ? $user->role->name : 'No Role' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mx-2 text-sm font-medium">Edit</a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 mx-2 text-sm font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        
                        @if($users->isEmpty())
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                Belum ada user yang diundang.
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
