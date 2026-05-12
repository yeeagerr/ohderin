@extends('layouts.dashboard_layout')

@section('title', 'Edit Pengguna')

@section('content')
<div class="flex-1 overflow-auto bg-gray-50/50">
    <div class="p-6">
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('users.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit Pengguna</h1>
                <p class="text-sm text-gray-500 mt-1">Perbarui data atau role pengguna.</p>
            </div>
        </div>

        @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li class="text-sm font-medium">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden max-w-2xl">
            <div class="p-6">
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru (Opsional)</label>
                            <input type="password" name="password" minlength="6" class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 transition-colors">
                            <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                            <select name="role_id" required class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 transition-colors">
                                <option value="" disabled>-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-6 rounded-xl transition-colors shadow-sm shadow-orange-200 flex items-center gap-2">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
