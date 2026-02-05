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
                    <span class="font-semibold text-lg"># 1</span>
                </div>
                <button class="p-2 hover:bg-red-50 text-red-500 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3 scrollbar-hide">
                <!-- Cart Item 1 -->
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Sunglasses</h4>
                            <div class="flex items-center space-x-2 mt-1">
                                <div class="flex items-center border border-gray-300 rounded-lg">
                                    <button class="px-2 py-1 hover:bg-gray-100 text-gray-600">−</button>
                                    <span class="px-3 py-1 border-x border-gray-300 text-sm font-medium">1</span>
                                    <button class="px-2 py-1 hover:bg-gray-100 text-gray-600">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="font-semibold text-gray-900">$90.00</p>
                </div>

                <!-- Cart Item 2 -->
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L4 5v6.09c0 5.05 3.41 9.76 8 10.91 4.59-1.15 8-5.86 8-10.91V5l-8-3z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Cap</h4>
                            <div class="flex items-center space-x-2 mt-1">
                                <div class="flex items-center border border-gray-300 rounded-lg">
                                    <button class="px-2 py-1 hover:bg-gray-100 text-gray-600">−</button>
                                    <span class="px-3 py-1 border-x border-gray-300 text-sm font-medium">1</span>
                                    <button class="px-2 py-1 hover:bg-gray-100 text-gray-600">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="font-semibold text-gray-900">$16.00</p>
                </div>

                <!-- Cart Item 3 -->
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Album</h4>
                            <div class="flex items-center space-x-2 mt-1">
                                <div class="flex items-center border border-gray-300 rounded-lg">
                                    <button class="px-2 py-1 hover:bg-gray-100 text-gray-600">−</button>
                                    <span class="px-3 py-1 border-x border-gray-300 text-sm font-medium">1</span>
                                    <button class="px-2 py-1 hover:bg-gray-100 text-gray-600">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="font-semibold text-gray-900">$10.00</p>
                </div>

                <!-- Cart Item 4 -->
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Woo Album #1</h4>
                            <div class="flex items-center space-x-2 mt-1">
                                <div class="flex items-center border border-gray-300 rounded-lg">
                                    <button class="px-2 py-1 hover:bg-gray-100 text-gray-600">−</button>
                                    <span class="px-3 py-1 border-x border-gray-300 text-sm font-medium">1</span>
                                    <button class="px-2 py-1 hover:bg-gray-100 text-gray-600">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="font-semibold text-gray-900">$10.00</p>
                </div>

                <!-- Cart Item 5 -->
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gray-700 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 3H3v18h18V3zm-2 16H5V5h14v14z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Man's formal shirt</h4>
                            <p class="text-xs text-gray-500">Green, Export</p>
                            <div class="flex items-center space-x-2 mt-1">
                                <div class="flex items-center border border-gray-300 rounded-lg">
                                    <button class="px-2 py-1 hover:bg-gray-100 text-gray-600">−</button>
                                    <span class="px-3 py-1 border-x border-gray-300 text-sm font-medium">1</span>
                                    <button class="px-2 py-1 hover:bg-gray-100 text-gray-600">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="font-semibold text-gray-900">$20.00</p>
                </div>

                <!-- Cart Item 6 -->
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21 3H3v18h18V3zm-2 16H5V5h14v14z"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">High Heel</h4>
                            <p class="text-xs text-gray-500">Black</p>
                            <div class="flex items-center space-x-2 mt-1">
                                <div class="flex items-center border border-gray-300 rounded-lg">
                                    <button class="px-2 py-1 hover:bg-gray-100 text-gray-600">−</button>
                                    <span class="px-3 py-1 border-x border-gray-300 text-sm font-medium">1</span>
                                    <button class="px-2 py-1 hover:bg-gray-100 text-gray-600">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="font-semibold text-gray-900">$45.00</p>
                </div>
            </div>

            <!-- Summary -->
            <div class="border-t border-gray-200 p-4 bg-gray-50">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Total</span>
                    <div class="text-right">
                        <span class="text-xs text-gray-500 mr-2">(6 items)</span>
                        <span class="font-bold text-lg">$191.00</span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Tax (5%)</span>
                    <span class="font-semibold">$9.55</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="p-4 space-y-3 border-t border-gray-200">
                <div class="grid grid-cols-3 gap-2">
                    <button class="px-3 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 text-sm font-medium transition">
                        Discount
                    </button>
                    <button class="px-3 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 text-sm font-medium transition">
                        Fee
                    </button>
                    <button class="px-3 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 text-sm font-medium transition">
                        Note
                    </button>
                </div>
                <button class="w-full px-4 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 text-sm font-medium transition">
                    🧮 Calculator
                </button>
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
                    <button class="px-4 py-3.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-medium flex items-center justify-center transition">
                        <span class="text-lg font-bold mr-1">$200.55</span>
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
                            <input type="text" placeholder="Scan barcode or search..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
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
                <div class="flex space-x-1 overflow-x-auto scrollbar-hide py-1">
                    <button class="px-4 py-2.5 bg-indigo-600 text-white rounded-lg font-medium whitespace-nowrap text-sm">
                        All Categories
                    </button>
                    <button class="px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg whitespace-nowrap text-sm transition">
                        Accessories
                    </button>
                    <button class="px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg whitespace-nowrap text-sm transition">
                        Basket
                    </button>
                    <button class="px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg whitespace-nowrap text-sm transition">
                        Clothing
                    </button>
                    <button class="px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg whitespace-nowrap text-sm transition">
                        Computer
                    </button>
                    <button class="px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg whitespace-nowrap text-sm transition">
                        Decor
                    </button>
                    <button class="px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg whitespace-nowrap text-sm transition">
                        Food
                    </button>
                    <button class="px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg whitespace-nowrap text-sm transition">
                        Electronics
                    </button>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="flex-1 overflow-y-auto p-5">
                <div class="grid grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-5">
                    <!-- Product Card 1 -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group">
                        <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-16 h-16 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                        </div>
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur rounded-full px-2.5 py-1 text-xs font-semibold text-gray-700 shadow">
                            Stock: 65
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Sunglasses</h3>
                        <p class="text-xl font-bold text-indigo-600">$90.00</p>
                        <button class="absolute bottom-4 right-4 p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Product Card 2 -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group">
                        <div class="aspect-square bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-16 h-16 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L4 5v6.09c0 5.05 3.41 9.76 8 10.91 4.59-1.15 8-5.86 8-10.91V5l-8-3zm6 9.09c0 4-2.55 7.7-6 8.83-3.45-1.13-6-4.82-6-8.83V6.31l6-2.12 6 2.12v4.78z"/>
                            </svg>
                        </div>
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur rounded-full px-2.5 py-1 text-xs font-semibold text-gray-700 shadow">
                            Stock: 42
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Cap</h3>
                        <div class="flex items-center gap-2">
                            <p class="text-xl font-bold text-indigo-600">$16.00</p>
                            <p class="text-sm text-gray-400 line-through">$18.00</p>
                        </div>
                        <button class="absolute bottom-4 right-4 p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Product Card 3 -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group">
                        <div class="aspect-square bg-gradient-to-br from-amber-100 to-amber-200 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-16 h-16 text-amber-700" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                            </svg>
                        </div>
                        <div class="absolute top-3 right-3 bg-red-100 rounded-full px-2.5 py-1 text-xs font-semibold text-red-600 shadow">
                            Out of Stock
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Belt</h3>
                        <div class="flex items-center gap-2">
                            <p class="text-xl font-bold text-indigo-600">$55.00</p>
                            <p class="text-sm text-gray-400 line-through">$65.00</p>
                        </div>
                        <button class="absolute bottom-4 right-4 p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Product Card 4 -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group">
                        <div class="aspect-square bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-16 h-16 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 6h-2.18c.11-.31.18-.65.18-1a2.996 2.996 0 00-5.5-1.65l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2z"/>
                            </svg>
                        </div>
                        <div class="absolute top-3 right-3 bg-red-100 rounded-full px-2.5 py-1 text-xs font-semibold text-red-600 shadow">
                            Out of Stock
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Beanie</h3>
                        <div class="flex items-center gap-2">
                            <p class="text-xl font-bold text-indigo-600">$18.00</p>
                            <p class="text-sm text-gray-400 line-through">$20.00</p>
                        </div>
                        <button class="absolute bottom-4 right-4 p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Product Card 5 - Food -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group">
                        <div class="aspect-square bg-gradient-to-br from-amber-200 to-amber-400 rounded-xl flex items-center justify-center mb-4">
                            <span class="text-5xl">🍛</span>
                        </div>
                        <div class="absolute top-3 right-3 bg-red-100 rounded-full px-2.5 py-1 text-xs font-semibold text-red-600 shadow">
                            Out of Stock
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Moglai</h3>
                        <div class="flex items-center gap-2">
                            <p class="text-xl font-bold text-indigo-600">$65.00</p>
                            <p class="text-sm text-gray-400 line-through">$75.00</p>
                        </div>
                        <button class="absolute bottom-4 right-4 p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Product Card 6 -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group">
                        <div class="aspect-square bg-gradient-to-br from-orange-200 to-orange-400 rounded-xl flex items-center justify-center mb-4">
                            <span class="text-5xl">🥟</span>
                        </div>
                        <div class="absolute top-3 right-3 bg-red-100 rounded-full px-2.5 py-1 text-xs font-semibold text-red-600 shadow">
                            Out of Stock
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Somucha</h3>
                        <div class="flex items-center gap-2">
                            <p class="text-xl font-bold text-indigo-600">$55.00</p>
                            <p class="text-sm text-gray-400 line-through">$60.00</p>
                        </div>
                        <button class="absolute bottom-4 right-4 p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Product Card 7 -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group">
                        <div class="aspect-square bg-gradient-to-br from-yellow-200 to-yellow-400 rounded-xl flex items-center justify-center mb-4">
                            <span class="text-5xl">🥪</span>
                        </div>
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur rounded-full px-2.5 py-1 text-xs font-semibold text-gray-700 shadow">
                            Stock: 25
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Sandwich</h3>
                        <p class="text-xl font-bold text-indigo-600">$10.00</p>
                        <button class="absolute bottom-4 right-4 p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Product Card 8 -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group">
                        <div class="aspect-square bg-gradient-to-br from-amber-300 to-amber-500 rounded-xl flex items-center justify-center mb-4">
                            <span class="text-5xl">🍗</span>
                        </div>
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur rounded-full px-2.5 py-1 text-xs font-semibold text-gray-700 shadow">
                            Stock: 18
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Grilled Chicken</h3>
                        <p class="text-xl font-bold text-indigo-600">$15.00</p>
                        <button class="absolute bottom-4 right-4 p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Product Card 9 -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group">
                        <div class="aspect-square bg-gradient-to-br from-orange-300 to-orange-500 rounded-xl flex items-center justify-center mb-4">
                            <span class="text-5xl">🌯</span>
                        </div>
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur rounded-full px-2.5 py-1 text-xs font-semibold text-gray-700 shadow">
                            Stock: 30
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Chicken Shawarma</h3>
                        <p class="text-xl font-bold text-indigo-600">$3.00</p>
                        <button class="absolute bottom-4 right-4 p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Product Card 10 -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group">
                        <div class="aspect-square bg-gradient-to-br from-red-300 to-red-500 rounded-xl flex items-center justify-center mb-4">
                            <span class="text-5xl">🍖</span>
                        </div>
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur rounded-full px-2.5 py-1 text-xs font-semibold text-gray-700 shadow">
                            Stock: 12
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">BBQ Flash</h3>
                        <p class="text-xl font-bold text-indigo-600">$5.00</p>
                        <button class="absolute bottom-4 right-4 p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Product Card 11 -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group">
                        <div class="aspect-square bg-gradient-to-br from-amber-100 to-amber-300 rounded-xl flex items-center justify-center mb-4">
                            <span class="text-5xl">☕</span>
                        </div>
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur rounded-full px-2.5 py-1 text-xs font-semibold text-gray-700 shadow">
                            Stock: 35
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Coffee</h3>
                        <p class="text-xl font-bold text-indigo-600">$20.00</p>
                        <button class="absolute bottom-4 right-4 p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Product Card 12 -->
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group">
                        <div class="aspect-square bg-gradient-to-br from-pink-200 to-pink-400 rounded-xl flex items-center justify-center mb-4">
                            <span class="text-5xl">🍦</span>
                        </div>
                        <div class="absolute top-3 right-3 bg-green-100 rounded-full px-2.5 py-1 text-xs font-semibold text-green-600 shadow">
                            50% OFF
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Ice Cream</h3>
                        <div class="flex items-center gap-2">
                            <p class="text-xl font-bold text-indigo-600">$5.00</p>
                            <p class="text-sm text-gray-400 line-through">$10.00</p>
                        </div>
                        <button class="absolute bottom-4 right-4 p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
@endsection