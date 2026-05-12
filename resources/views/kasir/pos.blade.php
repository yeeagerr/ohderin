@extends('layouts.kasir_layout')

@section('title', 'Point Of Sale')

@section('content')
    {{-- Hidden config element for JS --}}
    <div id="posConfig" class="hidden" data-route-products="{{ route('kasir.products') }}"
        data-route-checkout="{{ route('kasir.checkout') }}" data-route-hold="{{ route('kasir.hold') }}"
        data-route-drafts="{{ route('kasir.drafts') }}" data-csrf-token="{{ csrf_token() }}"
        data-route-register-status="{{ route('kasir.registers.status') }}"
        data-tables='@json($tables)' data-modifiers='@json($modifiers)' data-product-modifiers='@json($productModifiers)'
        data-registers='@json($registers)' data-active-register-session='@json($activeRegisterSession)'>
    </div>

    {{-- <div class="fixed top-3 right-5 z-30 bg-white border border-gray-200 rounded-xl px-4 py-2 shadow-sm flex items-center gap-3">
        <div id="activeRegisterInfo" class="text-sm text-gray-700">
            @if($activeRegisterSession)
                Session: <span class="font-semibold text-orange-600">{{ $activeRegisterSession->register->name }}</span>
            @else
                <span class="text-red-500 font-medium">Belum ada session kasir aktif</span>
            @endif
        </div>
        <button onclick="showRegisterPicker()" class="text-xs px-3 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50">Masuk Session</button>
        @if($activeRegisterSession)
            <button onclick='showCloseRegisterFromPos({{ $activeRegisterSession->id }}, @json($activeRegisterSession->register->name))' class="text-xs px-3 py-1.5 bg-gray-700 text-white rounded-lg hover:bg-gray-800">Close Register</button>
        @endif
    </div> --}}

    <!-- Cart Section -->
    <div id="cartSidebar" class="cart-sidebar cart-open bg-white border-r border-gray-200 flex flex-col flex-shrink-0">
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <button class="p-2 hover:bg-gray-100 rounded-lg" onclick="toggleCart()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <span class="font-semibold text-lg" id="orderNumber"># 1</span>
            </div>
            <div class="flex items-center space-x-2">
                <button id="deleteDraftBtn" onclick="deleteCurrentDraft()" class="p-2 hover:bg-red-50 text-red-500 rounded-lg hidden" title="Hapus draft">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
                <button onclick="clearCart()" class="p-2 hover:bg-red-50 text-red-500 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Cart Items -->
        <div id="cartItems" class="flex-1 overflow-y-auto p-4 space-y-3 scrollbar-hide">
            <!-- Cart items will be rendered here dynamically -->
            <div id="emptyCartMessage" class="flex flex-col items-center justify-center h-full text-gray-400">
                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-sm">Keranjang kosong</p>
                <p class="text-xs">Klik produk untuk menambahkan</p>
            </div>
        </div>

        <!-- Summary -->
        <div class="border-t border-gray-200 p-4 bg-gray-50">
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600">Total</span>
                <div class="text-right">
                    <span id="cartItemCount" class="text-xs text-gray-500 mr-2">(0 items)</span>
                    <span id="cartSubtotal" class="font-bold text-lg">Rp 0</span>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Tax (10%)</span>
                <span id="cartTax" class="font-semibold">Rp 0</span>
            </div>
            {{-- <div id="splitBillSummaryRow" class="flex justify-between items-center mt-2">
                <span class="text-gray-600">Split Bill</span>
                <span id="splitBillGroupDisplay" class="font-semibold text-gray-900">-</span>
            </div> --}}
        </div>

        <!-- Action Buttons -->
        <div class="p-4 space-y-3 border-t border-gray-200">
            <button
                class="w-full px-4 py-3 bg-gray-100 rounded-xl hover:bg-gray-200 font-medium flex items-center justify-center transition">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
                Add/Search Customer
            </button>
            <div class="grid grid-cols-2 gap-3">
                <button onclick="holdOrder()" id="holdBtn"
                    class="px-4 py-3.5 bg-gray-700 text-white rounded-xl hover:bg-gray-800 font-medium flex items-center justify-center transition relative disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z" />
                    </svg>
                    Hold
                    <span id="draftBadge"
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold"
                        style="display:none;">0</span>
                </button>
                <button onclick="showPaymentModal()" id="checkoutBtn"
                    class="px-4 py-3.5 bg-orange-500 text-white rounded-xl hover:bg-orange-600 font-medium flex items-center justify-center transition disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    <span id="cartTotal" class="text-lg font-bold mr-1">Rp 0</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Floating Toggle (visible when cart is closed) -->
    <button id="cartToggleFloating" onclick="toggleCart()" class="cart-toggle-floating" title="Buka keranjang"
        style="display: none;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <span id="cartBadge" class="cart-badge" style="display: none;">0</span>
    </button>

    <!-- Products Section -->
    <div class="flex-1 flex flex-col bg-gray-50 min-w-0">
        <!-- Top Bar -->
        <div class="bg-white border-b border-gray-200 p-4">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center space-x-3 flex-1 max-w-lg">
                    <div class="relative flex-1">
                        <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="searchInput" placeholder="Scan barcode or search..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    <button id="buttonInput"
                        class="px-5 py-2.5 bg-orange-500 text-white rounded-xl hover:bg-orange-600 font-medium transition whitespace-nowrap">
                        + Product
                    </button>
                </div>
                <div class="flex items-center space-x-2">
                    <div id="activeRegisterInfo" class="hidden sm:block text-xs text-gray-600 px-3 py-2 bg-gray-100 rounded-xl">
                        @if($activeRegisterSession)
                            Session: <span class="font-semibold text-orange-600">{{ $activeRegisterSession->register->name }}</span>
                        @else
                            <span class="text-red-500 font-medium">Belum ada session</span>
                        @endif
                    </div>
                    <button onclick="showRegisterPicker()" class="p-2.5 bg-orange-100 text-orange-500 rounded-xl hover:bg-orange-200 transition" title="Pilih / buka kasir">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                    </button>
                    <button class="p-2.5 bg-orange-100 text-orange-500 rounded-xl hover:bg-orange-200 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.11 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z" />
                        </svg>
                    </button>
                    <button class="p-2.5 bg-orange-100 text-orange-500 rounded-xl hover:bg-orange-200 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9h-4v4h-2v-4H9V9h4V5h2v4h4v2z" />
                        </svg>
                    </button>
                    <button class="p-2.5 bg-orange-100 text-orange-500 rounded-xl hover:bg-orange-200 transition"
                        onclick="window.location.href='/dashboard'">
                        <svg width="21" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.00023 9.00007V15.0001M9.00023 15.0001H15.0002M9.00023 15.0001L19 5.00001M21.6606 9.41051C22.5515 12.7467 21.6884 16.4538 19.0711 19.0711C15.1658 22.9764 8.83418 22.9764 4.92893 19.0711C1.02369 15.1659 1.02369 8.83424 4.92893 4.92899C7.54623 2.3117 11.2534 1.44852 14.5896 2.33944"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    @if($activeRegisterSession)
                        <button onclick='showCloseRegisterFromPos({{ $activeRegisterSession->id }}, @json($activeRegisterSession->register->name))' class="p-2.5 bg-gray-800 text-white rounded-xl hover:bg-gray-900 transition" title="Tutup kasir">
                            <svg class="w-5 h-5 m-0 p-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 8L22 12M22 12L18 16M22 12H9M15 4.20404C13.7252 3.43827 12.2452 3 10.6667 3C5.8802 3 2 7.02944 2 12C2 16.9706 5.8802 21 10.6667 21C12.2452 21 13.7252 20.5617 15 19.796" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="bg-white border-b border-gray-200 px-4">
            <div id="categoryTabs" class="flex space-x-1 overflow-x-auto scrollbar-hide py-1">
                <button onclick="filterByCategory('all')"
                    class="category-tab active px-4 py-2.5 bg-orange-500 text-white rounded-lg font-medium whitespace-nowrap text-sm"
                    data-category="all">
                    All Categories
                </button>
                @foreach($categories as $category)
                    <button onclick="filterByCategory('{{ $category['id'] }}')"
                        class="category-tab px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg whitespace-nowrap text-sm transition"
                        data-category="{{ $category['id'] }}">
                        {{ $category['name'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Products Grid -->
        <div class="flex-1 overflow-y-auto p-5" id="productsContainer">
            <div id="productsGrid" class="grid grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-5">
                @foreach($products as $product)
                    <div onclick='addToCart({{ $product->id }}, @json($product->name), {{ $product->price }}, @json($product->category), @json($product->modifiers))'
                        class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group">
                        <div
                            class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mb-4">
                            <span class="text-4xl">📦</span>
                        </div>
                        <div
                            class="absolute top-3 right-3 bg-white/90 backdrop-blur rounded-full px-2.5 py-1 text-xs font-semibold text-gray-700 shadow">
                            {{ $product->category->name }}
                        </div>
                        @if($product->modifiers->isNotEmpty())
                            <div class="absolute top-3 left-3 bg-orange-500 text-white rounded-full px-2.5 py-1 text-xs font-semibold shadow">
                                {{ $product->modifiers->count() }} modifier
                            </div>
                        @endif
                        <h3 class="font-semibold text-gray-900 mb-1 truncate" title="{{ $product->name }}">
                            {{ Str::limit($product->name, 20) }}
                        </h3>
                        <p class="text-xl font-bold text-orange-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <button
                            class="absolute bottom-4 right-4 p-2.5 bg-orange-500 text-white rounded-xl hover:bg-orange-600 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>

            <!-- Loading indicator -->
            <div id="loadingIndicator" class="hidden flex justify-center py-4">
                <svg class="animate-spin h-8 w-8 text-orange-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </div>

            <!-- Load more button -->
            @if($products->hasMorePages())
                <div id="loadMoreContainer" class="flex justify-center py-4">
                    <button onclick="loadMoreProducts()"
                        class="px-6 py-2.5 bg-orange-100 text-orange-500 rounded-xl hover:bg-orange-200 font-medium transition">
                        Load More Products
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div id="payment-modal-1">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Validasi Pesanan</h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Order Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Order</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" onclick="selectOrderType('dine_in')"
                                class="order-type-btn active px-4 py-3 border-2 border-orange-500 bg-orange-50 text-orange-500 rounded-xl font-medium"
                                data-type="dine_in">
                                🍽️ Dine In
                            </button>
                            <button type="button" onclick="selectOrderType('take_away')"
                                class="order-type-btn px-4 py-3 border-2 border-gray-300 rounded-xl font-medium"
                                data-type="take_away">
                                🛍️ Take Away
                            </button>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Split Bill (Bagi Rata)</label>
                            <button type="button" id="toggleSplitBillBtn" onclick="toggleSplitBill()"
                                class="px-3 py-2 bg-orange-100 text-orange-600 rounded-xl font-medium hover:bg-orange-200 transition">
                                Aktifkan
                            </button>
                        </div>
                        <div id="splitBillControls" class="hidden">
                            <div class="flex items-center gap-3">
                                <span class="text-gray-600">Berapa orang?</span>
                                <div class="flex items-center border border-gray-300 rounded-lg">
                                    <button type="button" onclick="updateSplitCount(-1)"
                                        class="px-3 py-1 hover:bg-gray-100 text-gray-600 font-bold">−</button>
                                    <input type="number" id="splitCountInput" onchange="calculateSplitBill()"
                                        onkeyup="calculateSplitBill()"
                                        class="w-16 px-2 py-1 text-center border-x border-gray-300 focus:outline-none" value="1"
                                        min="1">
                                    <button type="button" onclick="updateSplitCount(1)"
                                        class="px-3 py-1 hover:bg-gray-100 text-gray-600 font-bold">+</button>
                                </div>
                            </div>
                            <div id="splitBillResult" class="mt-3 p-3 bg-orange-50 rounded-xl hidden">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm text-orange-800" id="splitResultPersons">Total per orang:</span>
                                    <span id="splitPerPersonDisplay" class="font-bold text-orange-600 text-lg">Rp 0</span>
                                </div>
                                <div class="text-xs text-orange-700 italic">
                                    *Setiap orang membayar nominal di atas
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-200 flex space-x-3">
                    <button onclick="hidePaymentModal()"
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-xl font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button onclick="goToStep(2)"
                        class="flex-1 px-4 py-3 bg-orange-500 text-white rounded-xl font-medium hover:bg-orange-600 transition">
                        Lanjut ke Pembayaran
                    </button>
                </div>
            </div>

            <div id="payment-modal-2" class="hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Pembayaran</h2>
                </div>

                <div class="p-6 space-y-5">
                    <!-- Total -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Total Pembayaran</span>
                            <span id="modalTotal" class="text-2xl font-bold text-orange-500">Rp 0</span>
                        </div>
                    </div>

                    <!-- PAYMENT METHOD -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Metode Pembayaran
                        </label>

                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" onclick="selectPaymentMethod('cash')"
                                class="payment-method-btn active px-4 py-3 border-2 border-orange-500 bg-orange-50 text-orange-500 rounded-xl font-medium"
                                data-method="cash">
                                💵 Cash
                            </button>

                            <button type="button" onclick="selectPaymentMethod('qris')"
                                class="payment-method-btn px-4 py-3 border-2 border-gray-300 rounded-xl font-medium"
                                data-method="qris">
                                📱 QRIS
                            </button>

                            <button type="button" onclick="selectPaymentMethod('debit')"
                                class="payment-method-btn px-4 py-3 border-2 border-gray-300 rounded-xl font-medium"
                                data-method="debit">
                                💳 Debit
                            </button>

                            <button type="button" onclick="selectPaymentMethod('credit')"
                                class="payment-method-btn px-4 py-3 border-2 border-gray-300 rounded-xl font-medium"
                                data-method="credit">
                                💳 Credit
                            </button>
                        </div>
                    </div>

                    <!-- Paid Amount & Change -->
                    <div class="bg-orange-50 rounded-xl p-4 space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bayar</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                                <input type="number" id="paidAmountInput"
                                    class="w-full pl-10 pr-4 py-2 border-2 border-orange-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 text-xl font-bold"
                                    value="0">
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Kembalian</span>
                            <span id="changeDisplay" class="text-xl font-bold text-green-600">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-200 flex space-x-3">
                    <button onclick="goToStep(1)"
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-xl font-medium hover:bg-gray-50 transition">
                        Kembali
                    </button>

                    <button onclick="processCheckout()" id="processPaymentBtn"
                        class="flex-1 px-4 py-3 bg-orange-500 text-white rounded-xl font-medium hover:bg-orange-600 transition">
                        Proses Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm text-center p-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Transaksi Berhasil!</h2>
            <p id="successOrderNumber" class="text-gray-600 mb-6">Order #ORD-001</p>
            <div class="space-y-3">
                <button onclick="printReceipt()"
                    class="w-full px-4 py-3 bg-orange-500 text-white rounded-xl font-medium hover:bg-orange-600 transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Struk
                </button>
                <button onclick="hideSuccessModal()"
                    class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition">
                    Transaksi Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Drafts Panel Modal -->
    <div id="draftsModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[80vh] flex flex-col">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Draft Orders</h2>
                    <p class="text-sm text-gray-500">Order yang di-hold</p>
                </div>
                <button onclick="hideDraftsModal()" class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="draftsListContainer" class="flex-1 overflow-y-auto p-4 space-y-3">
                <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-sm">Belum ada draft</p>
                </div>
            </div>
        </div>
    </div>

    <div id="registerPickerModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">Pilih Kasir</h2>
                <button onclick="hideRegisterPicker()" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>
            <div id="registerPickerList" class="space-y-2"></div>
        </div>
    </div>

    <div id="openRegisterModalPos" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 space-y-5">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Buka Session Kasir</h2>
                <p id="openRegisterNamePos" class="text-sm text-gray-600 mt-1">Kasir: -</p>
            </div>
            <input type="hidden" id="openRegisterIdPos" />
            <div class="rounded-xl bg-orange-50 border border-orange-100 p-3 text-sm text-orange-800">
                Isi modal awal sesuai uang cash fisik di laci kasir.
            </div>
            <div>
                <label class="text-sm text-gray-700">Uang Modal Awal</label>
                <input id="openingCashInputPos" type="number" min="0" step="1000" placeholder="0" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-xl">
            </div>
            <div>
                <label class="text-sm text-gray-700">Catatan</label>
                <textarea id="openingNoteInputPos" rows="3" placeholder="Opsional" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-xl"></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button onclick="hideOpenRegisterPos()" class="px-4 py-2 border border-gray-300 rounded-xl">Batal</button>
                <button id="openRegisterSubmitBtnPos" onclick="submitOpenRegisterPos()" class="px-4 py-2 bg-orange-500 text-white rounded-xl">Buka Session</button>
            </div>
        </div>
    </div>

    <div id="closeRegisterModalPos" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg p-6 space-y-5">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Tutup Session Kasir</h2>
                <p id="closeRegisterNamePos" class="text-sm text-gray-600 mt-1">Kasir: -</p>
            </div>
            <input type="hidden" id="closeSessionIdPos" />
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-xl bg-gray-50 p-3">
                    <p class="text-gray-500">Modal Awal</p>
                    <p id="closeSummaryOpeningCashPos" class="font-bold text-gray-900">Rp 0</p>
                </div>
                <div class="rounded-xl bg-gray-50 p-3">
                    <p class="text-gray-500">Penjualan Cash</p>
                    <p id="closeSummaryCashSalesPos" class="font-bold text-gray-900">Rp 0</p>
                </div>
                <div class="rounded-xl bg-gray-50 p-3">
                    <p class="text-gray-500">Non-Cash</p>
                    <p id="closeSummaryNonCashSalesPos" class="font-bold text-gray-900">Rp 0</p>
                </div>
                <div class="rounded-xl bg-orange-50 p-3">
                    <p class="text-orange-700">Cash Seharusnya</p>
                    <p id="closeSummaryExpectedCashPos" class="font-bold text-orange-700">Rp 0</p>
                </div>
            </div>
            <div>
                <label class="text-sm text-gray-700">Total Uang Akhir</label>
                <input id="closingCashInputPos" type="number" min="0" step="1000" placeholder="Hitung uang cash fisik" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-xl">
                <p id="closeRegisterDifferencePos" class="text-xs text-gray-500 mt-1">Selisih akan dihitung otomatis.</p>
            </div>
            <div>
                <label class="text-sm text-gray-700">Catatan</label>
                <textarea id="closingNoteInputPos" rows="3" placeholder="Opsional, misalnya alasan selisih" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-xl"></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button onclick="hideCloseRegisterFromPos()" class="px-4 py-2 border border-gray-300 rounded-xl">Batal</button>
                <button id="closeRegisterSubmitBtnPos" onclick="submitCloseRegisterFromPos()" class="px-4 py-2 bg-gray-700 text-white rounded-xl">Tutup Session</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/pos.js') }}"></script>
@endsection
