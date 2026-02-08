@extends('layouts.kasir_layout')

@section('title', 'Point Of Sale')
    
@section('content')
        <!-- Cart Section -->
    <div class="w-80 xl:w-96 bg-white border-r border-gray-200 flex flex-col flex-shrink-0">
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <button class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <span class="font-semibold text-lg" id="orderNumber"># 1</span>
            </div>
            <button onclick="clearCart()" class="p-2 hover:bg-red-50 text-red-500 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Cart Items -->
        <div id="cartItems" class="flex-1 overflow-y-auto p-4 space-y-3 scrollbar-hide">
            <!-- Cart items will be rendered here dynamically -->
            <div id="emptyCartMessage" class="flex flex-col items-center justify-center h-full text-gray-400">
                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
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
        </div>

        <!-- Action Buttons -->
        <div class="p-4 space-y-3 border-t border-gray-200">
            <!-- <div class="grid grid-cols-3 gap-2">
                <button class="px-3 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 text-sm font-medium transition">
                    Discount
                </button>
                <button class="px-3 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 text-sm font-medium transition">
                    Fee
                </button>
                <button class="px-3 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 text-sm font-medium transition">
                    Note
                </button>
            </div> -->
            <button class="w-full px-4 py-3 bg-gray-100 rounded-xl hover:bg-gray-200 font-medium flex items-center justify-center transition">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                Add/Search Customer
            </button>
            <div class="grid grid-cols-2 gap-3">
                <button class="px-4 py-3.5 bg-gray-700 text-white rounded-xl hover:bg-gray-800 font-medium flex items-center justify-center transition">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z"/>
                    </svg>
                    Hold
                </button>
                <button onclick="showPaymentModal()" id="checkoutBtn" class="px-4 py-3.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-medium flex items-center justify-center transition disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <span id="cartTotal" class="text-lg font-bold mr-1">Rp 0</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="flex-1 flex flex-col bg-gray-50 min-w-0">
        <!-- Top Bar -->
        <div class="bg-white border-b border-gray-200 p-4">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center space-x-3 flex-1 max-w-lg">
                    <div class="relative flex-1">
                        <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" id="searchInput" placeholder="Scan barcode or search..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <button class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-medium transition whitespace-nowrap">
                        + Product
                    </button>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="p-2.5 bg-indigo-100 text-indigo-600 rounded-xl hover:bg-indigo-200 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.11 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                        </svg>
                    </button>
                    <button class="p-2.5 bg-indigo-100 text-indigo-600 rounded-xl hover:bg-indigo-200 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9h-4v4h-2v-4H9V9h4V5h2v4h4v2z"/>
                        </svg>
                    </button>
                    <button class="p-2.5 bg-indigo-100 text-indigo-600 rounded-xl hover:bg-indigo-200 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
                        </svg>
                    </button>
                    <button class="p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="bg-white border-b border-gray-200 px-4">
            <div id="categoryTabs" class="flex space-x-1 overflow-x-auto scrollbar-hide py-1">
                <button onclick="filterByCategory('all')" class="category-tab active px-4 py-2.5 bg-indigo-600 text-white rounded-lg font-medium whitespace-nowrap text-sm" data-category="all">
                    All Categories
                </button>
                @foreach($categories as $category)
                <button onclick="filterByCategory('{{ $category }}')" class="category-tab px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg whitespace-nowrap text-sm transition" data-category="{{ $category }}">
                    {{ $category }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Products Grid -->
        <div class="flex-1 overflow-y-auto p-5" id="productsContainer">
            <div id="productsGrid" class="grid grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-5">
                @foreach($products as $product)
                <div onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $product->category }}')" class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group">
                    <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mb-4">
                        <span class="text-4xl">📦</span>
                    </div>
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur rounded-full px-2.5 py-1 text-xs font-semibold text-gray-700 shadow">
                        {{ $product->category }}
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1 truncate" title="{{ $product->name }}">{{ Str::limit($product->name, 20) }}</h3>
                    <p class="text-xl font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <button class="absolute bottom-4 right-4 p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                        </svg>
                    </button>
                </div>
                @endforeach
            </div>
            
            <!-- Loading indicator -->
            <div id="loadingIndicator" class="hidden flex justify-center py-4">
                <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <!-- Load more button -->
            @if($products->hasMorePages())
            <div id="loadMoreContainer" class="flex justify-center py-4">
                <button onclick="loadMoreProducts()" class="px-6 py-2.5 bg-indigo-100 text-indigo-600 rounded-xl hover:bg-indigo-200 font-medium transition">
                    Load More Products
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Pembayaran</h2>
            </div>
            <div class="p-6 space-y-4">
                <!-- Order Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Order</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" onclick="selectOrderType('dine_in')" class="order-type-btn active px-4 py-3 border-2 border-indigo-600 bg-indigo-50 text-indigo-600 rounded-xl font-medium" data-type="dine_in">
                            🍽️ Dine In
                        </button>
                        <button type="button" onclick="selectOrderType('take_away')" class="order-type-btn px-4 py-3 border-2 border-gray-300 rounded-xl font-medium" data-type="take_away">
                            🛍️ Take Away
                        </button>
                    </div>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" onclick="selectPaymentMethod('cash')" class="payment-method-btn active px-4 py-3 border-2 border-indigo-600 bg-indigo-50 text-indigo-600 rounded-xl font-medium" data-method="cash">
                            💵 Cash
                        </button>
                        <button type="button" onclick="selectPaymentMethod('qris')" class="payment-method-btn px-4 py-3 border-2 border-gray-300 rounded-xl font-medium" data-method="qris">
                            📱 QRIS
                        </button>
                        <button type="button" onclick="selectPaymentMethod('debit')" class="payment-method-btn px-4 py-3 border-2 border-gray-300 rounded-xl font-medium" data-method="debit">
                            💳 Debit
                        </button>
                        <button type="button" onclick="selectPaymentMethod('credit')" class="payment-method-btn px-4 py-3 border-2 border-gray-300 rounded-xl font-medium" data-method="credit">
                            💳 Credit
                        </button>
                    </div>
                </div>

                <!-- Total -->
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Pembayaran</span>
                        <span id="modalTotal" class="text-2xl font-bold text-indigo-600">Rp 0</span>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-200 flex space-x-3">
                <button onclick="hidePaymentModal()" class="flex-1 px-4 py-3 border border-gray-300 rounded-xl font-medium hover:bg-gray-50 transition">
                    Batal
                </button>
                <button onclick="processCheckout()" id="processPaymentBtn" class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition">
                    Proses Pembayaran
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm text-center p-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Transaksi Berhasil!</h2>
            <p id="successOrderNumber" class="text-gray-600 mb-6">Order #ORD-001</p>
            <button onclick="hideSuccessModal()" class="w-full px-4 py-3 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition">
                Transaksi Baru
            </button>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Cart state
    let cart = [];
    let currentPage = 1;
    let currentCategory = 'all';
    let currentSearch = '';
    let selectedOrderType = 'dine_in';
    let selectedPaymentMethod = 'cash';
    const TAX_RATE = 0.10;

    // Format currency
    function formatCurrency(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    // Add product to cart
    function addToCart(productId, name, price, category) {
        const existingItem = cart.find(item => item.product_id === productId);
        
        if (existingItem) {
            existingItem.qty++;
        } else {
            cart.push({
                product_id: productId,
                name: name,
                price: price,
                qty: 1,
                category: category,
                note: null
            });
        }
        
        renderCart();
    }

    // Update item quantity
    function updateQuantity(productId, change) {
        const item = cart.find(item => item.product_id === productId);
        if (item) {
            item.qty += change;
            if (item.qty <= 0) {
                removeFromCart(productId);
            } else {
                renderCart();
            }
        }
    }

    // Remove item from cart
    function removeFromCart(productId) {
        cart = cart.filter(item => item.product_id !== productId);
        renderCart();
    }

    // Clear cart
    function clearCart() {
        cart = [];
        renderCart();
    }

    // Calculate totals
    function calculateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const tax = subtotal * TAX_RATE;
        const total = subtotal + tax;
        const itemCount = cart.reduce((sum, item) => sum + item.qty, 0);
        
        return { subtotal, tax, total, itemCount };
    }

    // Render cart
    function renderCart() {
        const cartContainer = document.getElementById('cartItems');
        const emptyMessage = document.getElementById('emptyCartMessage');
        const { subtotal, tax, total, itemCount } = calculateTotals();

        if (cart.length === 0) {
            cartContainer.innerHTML = `
                <div id="emptyCartMessage" class="flex flex-col items-center justify-center h-full text-gray-400">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="text-sm">Keranjang kosong</p>
                    <p class="text-xs">Klik produk untuk menambahkan</p>
                </div>
            `;
            document.getElementById('checkoutBtn').disabled = true;
        } else {
            let html = '';
            cart.forEach(item => {
                html += `
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center space-x-3 flex-1 min-w-0">
                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-lg">📦</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-medium text-gray-900 truncate" title="${item.name}">${item.name.substring(0, 15)}${item.name.length > 15 ? '...' : ''}</h4>
                                <div class="flex items-center space-x-2 mt-1">
                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                        <button onclick="updateQuantity(${item.product_id}, -1)" class="px-2 py-1 hover:bg-gray-100 text-gray-600">−</button>
                                        <span class="px-3 py-1 border-x border-gray-300 text-sm font-medium">${item.qty}</span>
                                        <button onclick="updateQuantity(${item.product_id}, 1)" class="px-2 py-1 hover:bg-gray-100 text-gray-600">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <p class="font-semibold text-gray-900">${formatCurrency(item.price * item.qty)}</p>
                            <button onclick="removeFromCart(${item.product_id})" class="p-1 text-red-500 hover:bg-red-50 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
            });
            cartContainer.innerHTML = html;
            document.getElementById('checkoutBtn').disabled = false;
        }

        // Update summary
        document.getElementById('cartItemCount').textContent = `(${itemCount} items)`;
        document.getElementById('cartSubtotal').textContent = formatCurrency(subtotal);
        document.getElementById('cartTax').textContent = formatCurrency(tax);
        document.getElementById('cartTotal').textContent = formatCurrency(total);
    }

    // Search products with debounce
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentSearch = e.target.value;
            currentPage = 1;
            loadProducts(true);
        }, 300);
    });

    // Filter by category
    function filterByCategory(category) {
        currentCategory = category;
        currentPage = 1;
        
        // Update active tab
        document.querySelectorAll('.category-tab').forEach(tab => {
            if (tab.dataset.category === category) {
                tab.classList.add('bg-indigo-600', 'text-white');
                tab.classList.remove('text-gray-600', 'hover:bg-gray-100');
            } else {
                tab.classList.remove('bg-indigo-600', 'text-white');
                tab.classList.add('text-gray-600', 'hover:bg-gray-100');
            }
        });
        
        loadProducts(true);
    }

    // Load products via AJAX
    function loadProducts(reset = false) {
        const grid = document.getElementById('productsGrid');
        const loading = document.getElementById('loadingIndicator');
        const loadMore = document.getElementById('loadMoreContainer');
        
        if (reset) {
            grid.innerHTML = '';
            currentPage = 1;
        }
        
        loading.classList.remove('hidden');
        if (loadMore) loadMore.classList.add('hidden');

        const params = new URLSearchParams({
            page: currentPage,
            search: currentSearch,
            category: currentCategory
        });

        fetch(`{{ route('kasir.products') }}?${params}`)
            .then(response => response.json())
            .then(data => {
                loading.classList.add('hidden');
                
                data.products.forEach(product => {
                    const div = document.createElement('div');
                    div.className = 'bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group';
                    div.onclick = () => addToCart(product.id, product.name, parseFloat(product.price), product.category);
                    div.innerHTML = `
                        <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mb-4">
                            <span class="text-4xl">📦</span>
                        </div>
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur rounded-full px-2.5 py-1 text-xs font-semibold text-gray-700 shadow">
                            ${product.category}
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1 truncate" title="${product.name}">${product.name.substring(0, 20)}${product.name.length > 20 ? '...' : ''}</h3>
                        <p class="text-xl font-bold text-indigo-600">${formatCurrency(parseFloat(product.price))}</p>
                        <button class="absolute bottom-4 right-4 p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                        </button>
                    `;
                    grid.appendChild(div);
                });

                if (data.hasMore) {
                    let loadMoreEl = document.getElementById('loadMoreContainer');
                    if (!loadMoreEl) {
                        loadMoreEl = document.createElement('div');
                        loadMoreEl.id = 'loadMoreContainer';
                        loadMoreEl.className = 'flex justify-center py-4';
                        loadMoreEl.innerHTML = `
                            <button onclick="loadMoreProducts()" class="px-6 py-2.5 bg-indigo-100 text-indigo-600 rounded-xl hover:bg-indigo-200 font-medium transition">
                                Load More Products
                            </button>
                        `;
                        document.getElementById('productsContainer').appendChild(loadMoreEl);
                    }
                    loadMoreEl.classList.remove('hidden');
                }
            });
    }

    // Load more products
    function loadMoreProducts() {
        currentPage++;
        loadProducts(false);
    }

    // Show payment modal
    function showPaymentModal() {
        if (cart.length === 0) return;
        
        const { total } = calculateTotals();
        document.getElementById('modalTotal').textContent = formatCurrency(total);
        document.getElementById('paymentModal').classList.remove('hidden');
    }

    // Hide payment modal
    function hidePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    function selectOrderType(type) {
        selectedOrderType = type;
        document.querySelectorAll('.order-type-btn').forEach(btn => {
            if (btn.dataset.type === type) {
                btn.classList.add('border-indigo-600', 'bg-indigo-50', 'text-indigo-600');
                btn.classList.remove('border-gray-300');
            } else {
                btn.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-600');
                btn.classList.add('border-gray-300');
            }
        });
    }

    function selectPaymentMethod(method) {
        selectedPaymentMethod = method;
        document.querySelectorAll('.payment-method-btn').forEach(btn => {
            if (btn.dataset.method === method) {
                btn.classList.add('border-indigo-600', 'bg-indigo-50', 'text-indigo-600');
                btn.classList.remove('border-gray-300');
            } else {
                btn.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-600');
                btn.classList.add('border-gray-300');
            }
        });
    }

    function processCheckout() {
        const btn = document.getElementById('processPaymentBtn');
        btn.disabled = true;
        btn.textContent = 'Processing...';

        const { total } = calculateTotals();

        fetch('{{ route('kasir.checkout') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                items: cart,
                order_type: selectedOrderType,
                payment_method: selectedPaymentMethod,
                total: total
            })
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.textContent = 'Proses Pembayaran';
            
            if (data.success) {
                hidePaymentModal();
                document.getElementById('successOrderNumber').textContent = 'Order ' + data.order_number;
                document.getElementById('successModal').classList.remove('hidden');
            } else {
                alert(data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.textContent = 'Proses Pembayaran';
            alert('Terjadi kesalahan: ' + error.message);
        });
    }

    // Hide success modal and reset
    function hideSuccessModal() {
        document.getElementById('successModal').classList.add('hidden');
        clearCart();
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderCart();
    });
</script>
@endsection