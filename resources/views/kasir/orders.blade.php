@extends('layouts.kasir_layout')

@section('title', 'Point Of Sale')
    
@section('content')
        <!-- Orders List Section -->
        <div class="flex-1 flex flex-col bg-gray-50 min-w-0">
            <!-- Top Bar -->
            <div class="bg-white border-b border-gray-200 p-3 lg:p-4">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 lg:gap-4">
                    <div>
                        <h1 class="text-xl lg:text-2xl font-bold text-gray-900">Orders</h1>
                        <p class="text-xs lg:text-sm text-gray-500">Manage and track all your orders</p>
                    </div>
                    <div class="flex items-center space-x-2 lg:space-x-3">
                        <div class="relative flex-1 lg:flex-none">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" placeholder="Search orders..." class="w-full lg:w-48 xl:w-64 pl-9 lg:pl-10 pr-4 py-2 lg:py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                        </div>
                        <button class="p-2 lg:p-2.5 bg-indigo-100 text-indigo-600 rounded-xl hover:bg-indigo-200 transition">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                        </button>
                        <button class="hidden lg:flex p-2.5 bg-indigo-100 text-indigo-600 rounded-xl hover:bg-indigo-200 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </button>
                        <button class="px-3 lg:px-4 py-2 lg:py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-medium transition flex items-center text-sm">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="hidden lg:inline">New Order</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Status Tabs -->
            <div class="bg-white border-b border-gray-200 px-3 lg:px-4">
                <div class="flex space-x-1 overflow-x-auto scrollbar-hide py-1">
                    <button class="px-3 lg:px-4 py-2 lg:py-2.5 bg-indigo-600 text-white rounded-lg font-medium whitespace-nowrap text-xs lg:text-sm flex items-center">
                        All
                        <span class="ml-1.5 lg:ml-2 bg-indigo-500 px-1.5 lg:px-2 py-0.5 rounded-full text-[10px] lg:text-xs">156</span>
                    </button>
                    <button class="px-3 lg:px-4 py-2 lg:py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg whitespace-nowrap text-xs lg:text-sm flex items-center transition">
                        <span class="w-1.5 h-1.5 lg:w-2 lg:h-2 bg-yellow-500 rounded-full mr-1.5 lg:mr-2"></span>
                        Pending
                        <span class="ml-1.5 lg:ml-2 bg-gray-200 px-1.5 lg:px-2 py-0.5 rounded-full text-[10px] lg:text-xs">12</span>
                    </button>
                    <button class="px-3 lg:px-4 py-2 lg:py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg whitespace-nowrap text-xs lg:text-sm flex items-center transition">
                        <span class="w-1.5 h-1.5 lg:w-2 lg:h-2 bg-blue-500 rounded-full mr-1.5 lg:mr-2"></span>
                        Processing
                        <span class="ml-1.5 lg:ml-2 bg-gray-200 px-1.5 lg:px-2 py-0.5 rounded-full text-[10px] lg:text-xs">8</span>
                    </button>
                    <button class="px-3 lg:px-4 py-2 lg:py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg whitespace-nowrap text-xs lg:text-sm flex items-center transition">
                        <span class="w-1.5 h-1.5 lg:w-2 lg:h-2 bg-green-500 rounded-full mr-1.5 lg:mr-2"></span>
                        Completed
                        <span class="ml-1.5 lg:ml-2 bg-gray-200 px-1.5 lg:px-2 py-0.5 rounded-full text-[10px] lg:text-xs">130</span>
                    </button>
                    <button class="px-3 lg:px-4 py-2 lg:py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg whitespace-nowrap text-xs lg:text-sm flex items-center transition">
                        <span class="w-1.5 h-1.5 lg:w-2 lg:h-2 bg-red-500 rounded-full mr-1.5 lg:mr-2"></span>
                        Cancelled
                        <span class="ml-1.5 lg:ml-2 bg-gray-200 px-1.5 lg:px-2 py-0.5 rounded-full text-[10px] lg:text-xs">6</span>
                    </button>
                </div>
            </div>

            <!-- Orders List -->
            <div class="flex-1 overflow-y-auto p-3 lg:p-5">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <!-- Table Header - Hidden on tablet, shown on desktop -->
                    <div class="hidden xl:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-50 border-b border-gray-200 text-sm font-medium text-gray-500">
                        <div class="col-span-1">
                            <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </div>
                        <div class="col-span-2">Order ID</div>
                        <div class="col-span-2">Customer</div>
                        <div class="col-span-2">Date & Time</div>
                        <div class="col-span-1">Items</div>
                        <div class="col-span-2">Total</div>
                        <div class="col-span-1">Status</div>
                        <div class="col-span-1 text-right">Actions</div>
                    </div>

                    <!-- Order Cards for Tablet / Table Rows for Desktop -->
                    
                    <!-- Order 1 - Completed -->
                    <div class="xl:grid xl:grid-cols-12 xl:gap-4 px-4 lg:px-6 py-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition" onclick="showOrderDetail()">
                        <!-- Tablet: Card Layout -->
                        <div class="xl:hidden">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-3">
                                    <div>
                                        <span class="font-semibold text-gray-900">#ORD-2024-001</span>
                                        <p class="text-xs text-gray-500">Dec 15, 2024 · 10:30 AM</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Completed</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-medium text-indigo-600">JD</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">John Doe</p>
                                        <p class="text-xs text-gray-500">6 items</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">$200.55</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Desktop: Table Row -->
                        <div class="hidden xl:contents">
                            <div class="col-span-1 flex items-center">
                                <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </div>
                            <div class="col-span-2 flex items-center">
                                <span class="font-semibold text-gray-900">#ORD-2024-001</span>
                            </div>
                            <div class="col-span-2 flex items-center">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-sm font-medium text-indigo-600">JD</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">John Doe</p>
                                    <p class="text-xs text-gray-500">john@email.com</p>
                                </div>
                            </div>
                            <div class="col-span-2 flex items-center text-gray-600">
                                <div>
                                    <p>Dec 15, 2024</p>
                                    <p class="text-xs text-gray-400">10:30 AM</p>
                                </div>
                            </div>
                            <div class="col-span-1 flex items-center">
                                <span class="text-gray-900">6 items</span>
                            </div>
                            <div class="col-span-2 flex items-center">
                                <span class="font-bold text-gray-900">$200.55</span>
                            </div>
                            <div class="col-span-1 flex items-center">
                                <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Completed</span>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">
                                <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Order 2 - Pending -->
                    <div class="xl:grid xl:grid-cols-12 xl:gap-4 px-4 lg:px-6 py-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition" onclick="showOrderDetail()">
                        <div class="xl:hidden">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-3">
                                    <div>
                                        <span class="font-semibold text-gray-900">#ORD-2024-002</span>
                                        <p class="text-xs text-gray-500">Dec 15, 2024 · 11:45 AM</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Pending</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-medium text-pink-600">SM</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Sarah Miller</p>
                                        <p class="text-xs text-gray-500">3 items</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">$85.00</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="hidden xl:contents">
                            <div class="col-span-1 flex items-center">
                                <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </div>
                            <div class="col-span-2 flex items-center">
                                <span class="font-semibold text-gray-900">#ORD-2024-002</span>
                            </div>
                            <div class="col-span-2 flex items-center">
                                <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-sm font-medium text-pink-600">SM</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Sarah Miller</p>
                                    <p class="text-xs text-gray-500">sarah@email.com</p>
                                </div>
                            </div>
                            <div class="col-span-2 flex items-center text-gray-600">
                                <div>
                                    <p>Dec 15, 2024</p>
                                    <p class="text-xs text-gray-400">11:45 AM</p>
                                </div>
                            </div>
                            <div class="col-span-1 flex items-center">
                                <span class="text-gray-900">3 items</span>
                            </div>
                            <div class="col-span-2 flex items-center">
                                <span class="font-bold text-gray-900">$85.00</span>
                            </div>
                            <div class="col-span-1 flex items-center">
                                <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Pending</span>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">
                                <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Order 3 - Processing -->
                    <div class="xl:grid xl:grid-cols-12 xl:gap-4 px-4 lg:px-6 py-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition" onclick="showOrderDetail()">
                        <div class="xl:hidden">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-3">
                                    <div>
                                        <span class="font-semibold text-gray-900">#ORD-2024-003</span>
                                        <p class="text-xs text-gray-500">Dec 15, 2024 · 12:15 PM</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Processing</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-medium text-green-600">MW</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Mike Wilson</p>
                                        <p class="text-xs text-gray-500">2 items</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">$45.50</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="hidden xl:contents">
                            <div class="col-span-1 flex items-center">
                                <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </div>
                            <div class="col-span-2 flex items-center">
                                <span class="font-semibold text-gray-900">#ORD-2024-003</span>
                            </div>
                            <div class="col-span-2 flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-sm font-medium text-green-600">MW</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Mike Wilson</p>
                                    <p class="text-xs text-gray-500">mike@email.com</p>
                                </div>
                            </div>
                            <div class="col-span-2 flex items-center text-gray-600">
                                <div>
                                    <p>Dec 15, 2024</p>
                                    <p class="text-xs text-gray-400">12:15 PM</p>
                                </div>
                            </div>
                            <div class="col-span-1 flex items-center">
                                <span class="text-gray-900">2 items</span>
                            </div>
                            <div class="col-span-2 flex items-center">
                                <span class="font-bold text-gray-900">$45.50</span>
                            </div>
                            <div class="col-span-1 flex items-center">
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Processing</span>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">
                                <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Order 4 - Completed -->
                    <div class="xl:grid xl:grid-cols-12 xl:gap-4 px-4 lg:px-6 py-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition" onclick="showOrderDetail()">
                        <div class="xl:hidden">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-3">
                                    <div>
                                        <span class="font-semibold text-gray-900">#ORD-2024-004</span>
                                        <p class="text-xs text-gray-500">Dec 14, 2024 · 04:20 PM</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Completed</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-medium text-purple-600">EJ</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Emily Johnson</p>
                                        <p class="text-xs text-gray-500">8 items</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">$320.00</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="hidden xl:contents">
                            <div class="col-span-1 flex items-center">
                                <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </div>
                            <div class="col-span-2 flex items-center">
                                <span class="font-semibold text-gray-900">#ORD-2024-004</span>
                            </div>
                            <div class="col-span-2 flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-sm font-medium text-purple-600">EJ</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Emily Johnson</p>
                                    <p class="text-xs text-gray-500">emily@email.com</p>
                                </div>
                            </div>
                            <div class="col-span-2 flex items-center text-gray-600">
                                <div>
                                    <p>Dec 14, 2024</p>
                                    <p class="text-xs text-gray-400">04:20 PM</p>
                                </div>
                            </div>
                            <div class="col-span-1 flex items-center">
                                <span class="text-gray-900">8 items</span>
                            </div>
                            <div class="col-span-2 flex items-center">
                                <span class="font-bold text-gray-900">$320.00</span>
                            </div>
                            <div class="col-span-1 flex items-center">
                                <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Completed</span>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">
                                <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Order 5 - Cancelled -->
                    <div class="xl:grid xl:grid-cols-12 xl:gap-4 px-4 lg:px-6 py-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition" onclick="showOrderDetail()">
                        <div class="xl:hidden">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-3">
                                    <div>
                                        <span class="font-semibold text-gray-900">#ORD-2024-005</span>
                                        <p class="text-xs text-gray-500">Dec 14, 2024 · 02:10 PM</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Cancelled</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-medium text-orange-600">RB</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Robert Brown</p>
                                        <p class="text-xs text-gray-500">1 item</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">$25.00</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="hidden xl:contents">
                            <div class="col-span-1 flex items-center">
                                <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </div>
                            <div class="col-span-2 flex items-center">
                                <span class="font-semibold text-gray-900">#ORD-2024-005</span>
                            </div>
                            <div class="col-span-2 flex items-center">
                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-sm font-medium text-orange-600">RB</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Robert Brown</p>
                                    <p class="text-xs text-gray-500">robert@email.com</p>
                                </div>
                            </div>
                            <div class="col-span-2 flex items-center text-gray-600">
                                <div>
                                    <p>Dec 14, 2024</p>
                                    <p class="text-xs text-gray-400">02:10 PM</p>
                                </div>
                            </div>
                            <div class="col-span-1 flex items-center">
                                <span class="text-gray-900">1 item</span>
                            </div>
                            <div class="col-span-2 flex items-center">
                                <span class="font-bold text-gray-900">$25.00</span>
                            </div>
                            <div class="col-span-1 flex items-center">
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Cancelled</span>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">
                                <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Order 6 - Completed -->
                    <div class="xl:grid xl:grid-cols-12 xl:gap-4 px-4 lg:px-6 py-4 hover:bg-gray-50 cursor-pointer transition" onclick="showOrderDetail()">
                        <div class="xl:hidden">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-3">
                                    <div>
                                        <span class="font-semibold text-gray-900">#ORD-2024-006</span>
                                        <p class="text-xs text-gray-500">Dec 14, 2024 · 11:00 AM</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Completed</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-medium text-teal-600">AT</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Amanda Taylor</p>
                                        <p class="text-xs text-gray-500">4 items</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">$156.75</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="hidden xl:contents">
                            <div class="col-span-1 flex items-center">
                                <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </div>
                            <div class="col-span-2 flex items-center">
                                <span class="font-semibold text-gray-900">#ORD-2024-006</span>
                            </div>
                            <div class="col-span-2 flex items-center">
                                <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-sm font-medium text-teal-600">AT</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Amanda Taylor</p>
                                    <p class="text-xs text-gray-500">amanda@email.com</p>
                                </div>
                            </div>
                            <div class="col-span-2 flex items-center text-gray-600">
                                <div>
                                    <p>Dec 14, 2024</p>
                                    <p class="text-xs text-gray-400">11:00 AM</p>
                                </div>
                            </div>
                            <div class="col-span-1 flex items-center">
                                <span class="text-gray-900">4 items</span>
                            </div>
                            <div class="col-span-2 flex items-center">
                                <span class="font-bold text-gray-900">$156.75</span>
                            </div>
                            <div class="col-span-1 flex items-center">
                                <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Completed</span>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">
                                <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex flex-col lg:flex-row items-center justify-between mt-4 lg:mt-5 gap-3">
                    <p class="text-xs lg:text-sm text-gray-500">Showing 1-6 of 156 orders</p>
                    <div class="flex items-center space-x-1 lg:space-x-2">
                        <button class="px-3 lg:px-4 py-1.5 lg:py-2 border border-gray-300 rounded-xl text-xs lg:text-sm font-medium text-gray-400 cursor-not-allowed" disabled>
                            Prev
                        </button>
                        <button class="px-3 lg:px-4 py-1.5 lg:py-2 bg-indigo-600 text-white rounded-xl text-xs lg:text-sm font-medium">1</button>
                        <button class="px-3 lg:px-4 py-1.5 lg:py-2 border border-gray-300 rounded-xl text-xs lg:text-sm font-medium text-gray-600 hover:bg-gray-50 transition">2</button>
                        <button class="px-3 lg:px-4 py-1.5 lg:py-2 border border-gray-300 rounded-xl text-xs lg:text-sm font-medium text-gray-600 hover:bg-gray-50 transition">3</button>
                        <span class="px-1 lg:px-2 text-gray-400">...</span>
                        <button class="px-3 lg:px-4 py-1.5 lg:py-2 border border-gray-300 rounded-xl text-xs lg:text-sm font-medium text-gray-600 hover:bg-gray-50 transition">26</button>
                        <button class="px-3 lg:px-4 py-1.5 lg:py-2 border border-gray-300 rounded-xl text-xs lg:text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Detail Panel - Slide from right on tablet -->
        <div id="orderDetailPanel" class="fixed inset-0 z-40 hidden lg:relative lg:inset-auto">
            <!-- Backdrop for tablet -->
            <div class="absolute inset-0 bg-black/50 lg:hidden" onclick="closeOrderDetail()"></div>
            
            <!-- Panel -->
            <div class="absolute right-0 top-0 h-full w-80 lg:w-80 xl:w-96 bg-white border-l border-gray-200 flex flex-col shadow-xl lg:shadow-none lg:relative">
                <!-- Header -->
                <div class="p-3 lg:p-4 border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-base lg:text-lg text-gray-900">#ORD-2024-001</h2>
                        <p class="text-xs lg:text-sm text-gray-500">Dec 15, 2024 at 10:30 AM</p>
                    </div>
                    <button class="p-2 hover:bg-gray-100 rounded-lg transition" onclick="closeOrderDetail()">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Status Badge -->
                <div class="px-3 lg:px-4 py-2.5 lg:py-3 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <span class="text-xs lg:text-sm text-gray-500">Status</span>
                        <span class="px-2.5 lg:px-3 py-1 lg:py-1.5 bg-green-100 text-green-700 rounded-full text-xs lg:text-sm font-medium">Completed</span>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="p-3 lg:p-4 border-b border-gray-100">
                    <h3 class="text-xs lg:text-sm font-medium text-gray-500 mb-2 lg:mb-3">Customer Information</h3>
                    <div class="flex items-center">
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-base lg:text-lg font-medium text-indigo-600">JD</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm lg:text-base">John Doe</p>
                            <p class="text-xs lg:text-sm text-gray-500">john@email.com</p>
                            <p class="text-xs lg:text-sm text-gray-500">+1 234 567 890</p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="flex-1 overflow-y-auto p-3 lg:p-4 scrollbar-hide">
                    <h3 class="text-xs lg:text-sm font-medium text-gray-500 mb-2 lg:mb-3">Order Items (6)</h3>
                    <div class="space-y-2 lg:space-y-3">
                        <!-- Item 1 -->
                        <div class="flex items-center justify-between p-2.5 lg:p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center">
                                <div class="w-8 h-8 lg:w-10 lg:h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-2 lg:mr-3">
                                    <span class="text-base lg:text-lg">🕶️</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-xs lg:text-sm">Sunglasses</p>
                                    <p class="text-[10px] lg:text-xs text-gray-500">Qty: 1</p>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-900 text-xs lg:text-sm">$90.00</p>
                        </div>

                        <!-- Item 2 -->
                        <div class="flex items-center justify-between p-2.5 lg:p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center">
                                <div class="w-8 h-8 lg:w-10 lg:h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-2 lg:mr-3">
                                    <span class="text-base lg:text-lg">🧢</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-xs lg:text-sm">Cap</p>
                                    <p class="text-[10px] lg:text-xs text-gray-500">Qty: 1</p>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-900 text-xs lg:text-sm">$16.00</p>
                        </div>

                        <!-- Item 3 -->
                        <div class="flex items-center justify-between p-2.5 lg:p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center">
                                <div class="w-8 h-8 lg:w-10 lg:h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-2 lg:mr-3">
                                    <span class="text-base lg:text-lg">💿</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-xs lg:text-sm">Album</p>
                                    <p class="text-[10px] lg:text-xs text-gray-500">Qty: 1</p>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-900 text-xs lg:text-sm">$10.00</p>
                        </div>

                        <!-- Item 4 -->
                        <div class="flex items-center justify-between p-2.5 lg:p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center">
                                <div class="w-8 h-8 lg:w-10 lg:h-10 bg-green-100 rounded-lg flex items-center justify-center mr-2 lg:mr-3">
                                    <span class="text-base lg:text-lg">💿</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-xs lg:text-sm">Woo Album #1</p>
                                    <p class="text-[10px] lg:text-xs text-gray-500">Qty: 1</p>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-900 text-xs lg:text-sm">$10.00</p>
                        </div>

                        <!-- Item 5 -->
                        <div class="flex items-center justify-between p-2.5 lg:p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center">
                                <div class="w-8 h-8 lg:w-10 lg:h-10 bg-gray-700 rounded-lg flex items-center justify-center mr-2 lg:mr-3">
                                    <span class="text-base lg:text-lg">👔</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-xs lg:text-sm">Man's formal shirt</p>
                                    <p class="text-[10px] lg:text-xs text-gray-500">Green · Qty: 1</p>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-900 text-xs lg:text-sm">$20.00</p>
                        </div>

                        <!-- Item 6 -->
                        <div class="flex items-center justify-between p-2.5 lg:p-3 bg-gray-50 rounded-xl">
                            <div class="flex items-center">
                                <div class="w-8 h-8 lg:w-10 lg:h-10 bg-red-400 rounded-lg flex items-center justify-center mr-2 lg:mr-3">
                                    <span class="text-base lg:text-lg">👠</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-xs lg:text-sm">High Heel</p>
                                    <p class="text-[10px] lg:text-xs text-gray-500">Black · Qty: 1</p>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-900 text-xs lg:text-sm">$45.00</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="border-t border-gray-200 p-3 lg:p-4 bg-gray-50">
                    <h3 class="text-xs lg:text-sm font-medium text-gray-500 mb-2 lg:mb-3">Payment Summary</h3>
                    <div class="space-y-1.5 lg:space-y-2">
                        <div class="flex justify-between text-xs lg:text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900">$191.00</span>
                        </div>
                        <div class="flex justify-between text-xs lg:text-sm">
                            <span class="text-gray-600">Tax (5%)</span>
                            <span class="text-gray-900">$9.55</span>
                        </div>
                        <div class="flex justify-between text-xs lg:text-sm">
                            <span class="text-gray-600">Discount</span>
                            <span class="text-green-600">-$0.00</span>
                        </div>
                        <div class="border-t border-gray-200 pt-2 mt-2">
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-900 text-sm lg:text-base">Total</span>
                                <span class="font-bold text-lg lg:text-xl text-indigo-600">$200.55</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mt-3 lg:mt-4 p-2.5 lg:p-3 bg-white rounded-xl border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-7 h-7 lg:w-8 lg:h-8 bg-green-100 rounded-lg flex items-center justify-center mr-2 lg:mr-3">
                                    <svg class="w-3.5 h-3.5 lg:w-4 lg:h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-xs lg:text-sm">Cash</p>
                                    <p class="text-[10px] lg:text-xs text-gray-500">Paid in full</p>
                                </div>
                            </div>
                            <span class="text-green-600 text-xs lg:text-sm font-medium">✓ Paid</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="p-3 lg:p-4 border-t border-gray-200 space-y-2">
                    <div class="grid grid-cols-2 gap-2">
                        <button class="px-3 lg:px-4 py-2 lg:py-2.5 border border-gray-300 rounded-xl text-xs lg:text-sm font-medium hover:bg-gray-50 transition flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 lg:w-4 lg:h-4 mr-1.5 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print
                        </button>
                        <button class="px-3 lg:px-4 py-2 lg:py-2.5 border border-gray-300 rounded-xl text-xs lg:text-sm font-medium hover:bg-gray-50 transition flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 lg:w-4 lg:h-4 mr-1.5 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Email
                        </button>
                    </div>
                    <button class="w-full px-3 lg:px-4 py-2 lg:py-2.5 border border-red-200 text-red-600 rounded-xl text-xs lg:text-sm font-medium hover:bg-red-50 transition flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 lg:w-4 lg:h-4 mr-1.5 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        Request Refund
                    </button>
                </div>
            </div>
        </div>

        <script>

        function showOrderDetail() {
            const panel = document.getElementById('orderDetailPanel');
            panel.classList.remove('hidden');
            document.body.style.overflow = window.innerWidth < 1024 ? 'hidden' : '';
        }

        function closeOrderDetail() {
            const panel = document.getElementById('orderDetailPanel');
            panel.classList.add('hidden');
            document.body.style.overflow = '';
        }
        </script>
@endsection
