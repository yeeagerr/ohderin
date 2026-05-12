@extends('layouts.dashboard_layout')

@section('title', 'Pengaturan Aplikasi')

@section('content')
<div class="flex-1 overflow-auto bg-gray-50/50">
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Pengaturan Aplikasi</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola konfigurasi sistem Ohderin.</p>
        </div>

        @if (session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex h-6 items-center">
                                <input id="require_recipe_to_sell_product" name="require_recipe_to_sell_product" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-600" {{ isset($settings['require_recipe_to_sell_product']) && $settings['require_recipe_to_sell_product'] == '1' ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label for="require_recipe_to_sell_product" class="font-medium text-gray-900">Validasi Resep Saat Penjualan</label>
                                <p class="text-gray-500">Apakah produk bisa dijual hanya jika memiliki resep yang valid? Jika dicentang, produk tanpa resep (atau stok resep kurang) tidak bisa dijual.</p>
                            </div>
                        </div>

                        <!-- Add more settings here in the future -->
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-6 rounded-xl transition-colors shadow-sm shadow-orange-200 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
