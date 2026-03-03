@extends('layouts.kasir_layout')

@section('title', 'Orders')
    
@section('content')
    {{-- Hidden config element for JS --}}
    <div id="ordersConfig" class="hidden"
         data-route-orders-data="{{ route('kasir.orders.data') }}"
         data-csrf-token="{{ csrf_token() }}">
    </div>

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
                            <input type="text" id="ordersSearchInput" placeholder="Cari order..." class="w-full lg:w-48 xl:w-64 pl-9 lg:pl-10 pr-4 py-2 lg:py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                        </div>
                        <a href="{{ route('kasir.pos') }}" class="px-3 lg:px-4 py-2 lg:py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-medium transition flex items-center text-sm">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="hidden lg:inline">New Order</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status Tabs -->
            <div class="bg-white border-b border-gray-200 px-3 lg:px-4">
                <div id="statusTabs" class="flex space-x-1 overflow-x-auto scrollbar-hide py-1">
                    <!-- Tabs will be rendered dynamically -->
                    <button class="px-3 lg:px-4 py-2 lg:py-2.5 bg-indigo-600 text-white rounded-lg font-medium whitespace-nowrap text-xs lg:text-sm flex items-center">
                        All
                        <span class="ml-1.5 lg:ml-2 bg-indigo-500 px-1.5 lg:px-2 py-0.5 rounded-full text-[10px] lg:text-xs">...</span>
                    </button>
                </div>
            </div>

            <!-- Orders List -->
            <div class="flex-1 overflow-y-auto p-3 lg:p-5">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div id="ordersListContainer">
                        <!-- Orders will be rendered dynamically -->
                        <div class="flex justify-center py-16">
                            <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div id="paginationContainer" class="flex flex-col lg:flex-row items-center justify-between mt-4 lg:mt-5 gap-3">
                    <!-- Pagination will be rendered dynamically -->
                </div>
            </div>
        </div>

        <!-- Order Detail Panel - Slide from right on tablet -->
        <div id="orderDetailPanel" class="fixed inset-0 z-40 hidden lg:relative lg:inset-auto">
            <!-- Backdrop for tablet -->
            <div class="absolute inset-0 bg-black/50 lg:hidden" onclick="closeOrderDetail()"></div>
            
            <!-- Panel -->
            <div class="absolute right-0 top-0 h-full w-80 lg:w-80 xl:w-96 bg-white border-l border-gray-200 flex flex-col shadow-xl lg:shadow-none lg:relative">
                <div id="orderDetailContent" class="flex flex-col h-full">
                    <!-- Detail content will be loaded dynamically -->
                    <div class="flex justify-center items-center h-full text-gray-400">
                        <p class="text-sm">Pilih order untuk melihat detail</p>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
<script src="{{ asset('js/orders.js') }}"></script>
@endsection
