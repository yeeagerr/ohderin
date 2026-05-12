@extends('layouts.dashboard_layout')

@section('title', 'Tambah Role')

@section('content')
<div class="flex-1 overflow-auto bg-gray-50/50">
    <div class="p-6">
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('roles.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Tambah Role Baru</h1>
                <p class="text-sm text-gray-500 mt-1">Buat peran baru dan pilih hak akses menunya.</p>
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

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden max-w-4xl">
            <div class="p-6">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Role <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Kasir, Admin Dapur" class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-orange-500 focus:ring-orange-500 transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Hak Akses Menu</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($permissions as $key => $label)
                                <div class="flex items-start bg-gray-50 p-3 rounded-xl border border-gray-100">
                                    <div class="flex h-5 items-center">
                                        <input id="perm_{{ $key }}" name="permissions[]" value="{{ $key }}" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-600">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="perm_{{ $key }}" class="font-medium text-gray-700 cursor-pointer">{{ $label }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-6 rounded-xl transition-colors shadow-sm shadow-orange-200 flex items-center gap-2">
                            Simpan Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
