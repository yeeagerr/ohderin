// Orders page JavaScript
let ordersCurrentPage = 1;
let ordersCurrentStatus = "all";
let ordersSearchQuery = "";
let ordersSearchTimeout;

let routeOrdersData = "";
let ordersCSRFToken = "";
localStorage.setItem("pos_resume_cart", JSON.stringify([]));
function ordersFormatCurrency(amount) {
    return "Rp " + new Intl.NumberFormat("id-ID").format(amount);
}

function getStatusBadge(status) {
    const styles = {
        completed: {
            bg: "bg-green-100",
            text: "text-green-700",
            dot: "bg-green-500",
            label: "Completed",
        },
        draft: {
            bg: "bg-orange-100",
            text: "text-orange-700",
            dot: "bg-orange-500",
            label: "Draft",
        },
    };
    return styles[status] || styles.completed;
}

function getInitials(name) {
    return name
        .split(" ")
        .map((w) => w[0])
        .join("")
        .substring(0, 2)
        .toUpperCase();
}

const avatarColors = [
    { bg: "bg-indigo-100", text: "text-indigo-600" },
    { bg: "bg-pink-100", text: "text-pink-600" },
    { bg: "bg-green-100", text: "text-green-600" },
    { bg: "bg-purple-100", text: "text-purple-600" },
    { bg: "bg-orange-100", text: "text-orange-600" },
    { bg: "bg-teal-100", text: "text-teal-600" },
];

function getAvatarColor(name) {
    let hash = 0;
    for (let i = 0; i < name.length; i++)
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    return avatarColors[Math.abs(hash) % avatarColors.length];
}

function getOrderTypeLabel(type) {
    return type === "dine_in" ? "🍽️ Dine In" : "🛍️ Take Away";
}

function getPaymentLabel(method) {
    const labels = {
        cash: "💵 Cash",
        qris: "📱 QRIS",
        debit: "💳 Debit",
        credit: "💳 Credit",
    };
    return labels[method] || method;
}

// LOAD ORDERS

function loadOrders(reset = false) {
    if (reset) ordersCurrentPage = 1;

    const params = new URLSearchParams({
        page: ordersCurrentPage,
        status: ordersCurrentStatus,
        search: ordersSearchQuery,
    });

    const ordersContainer = document.getElementById("ordersListContainer");
    ordersContainer.innerHTML = `
        <div class="flex justify-center py-16">
            <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    `;

    fetch(`${routeOrdersData}?${params}`)
        .then((r) => r.json())
        .then((data) => {
            renderOrders(data.orders);
            renderPagination(data.pagination);
            renderStatusTabs(data.counts);
        })
        .catch(() => {
            ordersContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-sm">Gagal memuat data orders</p>
                    <button onclick="loadOrders()" class="mt-3 px-4 py-2 bg-indigo-100 text-indigo-600 rounded-lg text-sm font-medium hover:bg-indigo-200 transition">Coba Lagi</button>
                </div>
            `;
        });
}

function renderOrders(orders) {
    const container = document.getElementById("ordersListContainer");

    if (!orders || orders.length === 0) {
        container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm">Tidak ada orders ditemukan</p>
            </div>
        `;
        return;
    }

    let html = `
        <!-- Table Header - Desktop -->
        <div class="hidden xl:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-50 border-b border-gray-200 text-sm font-medium text-gray-500">
            <div class="col-span-3">Order ID</div>
            <div class="col-span-2">Kasir</div>
            <div class="col-span-2">Tanggal</div>
            <div class="col-span-1">Items</div>
            <div class="col-span-2">Total</div>
            <div class="col-span-1">Status</div>
            <div class="col-span-1 text-right">Tipe</div>
        </div>
    `;

    orders.forEach((order) => {
        const badge = getStatusBadge(order.status);
        const color = getAvatarColor(order.cashier_name);
        const initials = getInitials(order.cashier_name);

        html += `
            <!-- Order Row -->
            <div class="xl:grid xl:grid-cols-12 xl:gap-4 px-4 lg:px-6 py-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition" onclick="showOrderDetail(${order.id})">
                <!-- Tablet: Card Layout -->
                <div class="xl:hidden">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center">
                            <div>
                                <span class="font-semibold text-gray-900">#${order.order_number}</span>
                                <p class="text-xs text-gray-500">${order.created_at} · ${order.created_time}</p>
                            </div>
                        </div>
                        <span class="px-2 py-1 ${badge.bg} ${badge.text} rounded-full text-xs font-medium">${badge.label}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 ${color.bg} rounded-full flex items-center justify-center mr-2">
                                <span class="text-xs font-medium ${color.text}">${initials}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">${order.cashier_name}</p>
                                <p class="text-xs text-gray-500">${order.items_count} item</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900">${ordersFormatCurrency(parseFloat(order.total))}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Desktop: Table Row -->
                <div class="hidden xl:contents">
                    <div class="col-span-3 flex items-center">
                        <span class="font-semibold text-gray-900">#${order.order_number}</span>
                    </div>
                    <div class="col-span-2 flex items-center">
                        <div class="w-8 h-8 ${color.bg} rounded-full flex items-center justify-center mr-3">
                            <span class="text-sm font-medium ${color.text}">${initials}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">${order.cashier_name}</p>
                        </div>
                    </div>
                    <div class="col-span-2 flex items-center text-gray-600">
                        <div>
                            <p>${order.created_at}</p>
                            <p class="text-xs text-gray-400">${order.created_time}</p>
                        </div>
                    </div>
                    <div class="col-span-1 flex items-center">
                        <span class="text-gray-900">${order.items_count} item</span>
                    </div>
                    <div class="col-span-2 flex items-center">
                        <span class="font-bold text-gray-900">${ordersFormatCurrency(parseFloat(order.total))}</span>
                    </div>
                    <div class="col-span-1 flex items-center">
                        <span class="px-2.5 py-1 ${badge.bg} ${badge.text} rounded-full text-xs font-medium">${badge.label}</span>
                    </div>
                    <div class="col-span-1 flex items-center justify-end">
                        <span class="text-xs text-gray-500">${getOrderTypeLabel(order.order_type)}</span>
                    </div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

function renderPagination(pagination) {
    const container = document.getElementById("paginationContainer");
    if (!pagination || pagination.total === 0) {
        container.innerHTML = "";
        return;
    }

    let pagesHtml = "";
    const maxPages = Math.min(pagination.last_page, 5);
    let startPage = Math.max(1, pagination.current_page - 2);
    let endPage = Math.min(pagination.last_page, startPage + maxPages - 1);
    if (endPage - startPage < maxPages - 1)
        startPage = Math.max(1, endPage - maxPages + 1);

    for (let i = startPage; i <= endPage; i++) {
        if (i === pagination.current_page) {
            pagesHtml += `<button class="px-3 lg:px-4 py-1.5 lg:py-2 bg-indigo-600 text-white rounded-xl text-xs lg:text-sm font-medium">${i}</button>`;
        } else {
            pagesHtml += `<button onclick="goToPage(${i})" class="px-3 lg:px-4 py-1.5 lg:py-2 border border-gray-300 rounded-xl text-xs lg:text-sm font-medium text-gray-600 hover:bg-gray-50 transition">${i}</button>`;
        }
    }

    if (endPage < pagination.last_page) {
        pagesHtml += `<span class="px-1 lg:px-2 text-gray-400">...</span>`;
        pagesHtml += `<button onclick="goToPage(${pagination.last_page})" class="px-3 lg:px-4 py-1.5 lg:py-2 border border-gray-300 rounded-xl text-xs lg:text-sm font-medium text-gray-600 hover:bg-gray-50 transition">${pagination.last_page}</button>`;
    }

    container.innerHTML = `
        <p class="text-xs lg:text-sm text-gray-500">Showing ${pagination.from || 0}-${pagination.to || 0} of ${pagination.total} orders</p>
        <div class="flex items-center space-x-1 lg:space-x-2">
            <button ${pagination.current_page <= 1 ? "disabled" : `onclick="goToPage(${pagination.current_page - 1})"`} class="px-3 lg:px-4 py-1.5 lg:py-2 border border-gray-300 rounded-xl text-xs lg:text-sm font-medium ${pagination.current_page <= 1 ? "text-gray-400 cursor-not-allowed" : "text-gray-600 hover:bg-gray-50 transition"}">
                Prev
            </button>
            ${pagesHtml}
            <button ${pagination.current_page >= pagination.last_page ? "disabled" : `onclick="goToPage(${pagination.current_page + 1})"`} class="px-3 lg:px-4 py-1.5 lg:py-2 border border-gray-300 rounded-xl text-xs lg:text-sm font-medium ${pagination.current_page >= pagination.last_page ? "text-gray-400 cursor-not-allowed" : "text-gray-600 hover:bg-gray-50 transition"}">
                Next
            </button>
        </div>
    `;
}

function renderStatusTabs(counts) {
    const tabs = [
        { status: "all", label: "All", count: counts.all, dot: null },
        {
            status: "completed",
            label: "Completed",
            count: counts.completed,
            dot: "bg-green-500",
        },
        {
            status: "draft",
            label: "Draft",
            count: counts.draft,
            dot: "bg-orange-500",
        },
    ];

    const container = document.getElementById("statusTabs");
    let html = "";

    tabs.forEach((tab) => {
        const isActive = ordersCurrentStatus === tab.status;
        html += `
            <button onclick="filterByStatus('${tab.status}')" class="px-3 lg:px-4 py-2 lg:py-2.5 ${isActive ? "bg-indigo-600 text-white" : "text-gray-600 hover:bg-gray-100"} rounded-lg font-medium whitespace-nowrap text-xs lg:text-sm flex items-center transition">
                ${tab.dot ? `<span class="w-1.5 h-1.5 lg:w-2 lg:h-2 ${tab.dot} rounded-full mr-1.5 lg:mr-2"></span>` : ""}
                ${tab.label}
                <span class="ml-1.5 lg:ml-2 ${isActive ? "bg-indigo-500" : "bg-gray-200"} px-1.5 lg:px-2 py-0.5 rounded-full text-[10px] lg:text-xs">${tab.count}</span>
            </button>
        `;
    });

    container.innerHTML = html;
}

function filterByStatus(status) {
    ordersCurrentStatus = status;
    loadOrders(true);
}

function goToPage(page) {
    ordersCurrentPage = page;
    loadOrders(false);
}

// =============================================
// ORDER DETAIL
// =============================================

let currentOrderDetail = null;

function showOrderDetail(orderId) {
    const panel = document.getElementById("orderDetailPanel");
    panel.classList.remove("hidden");
    document.body.style.overflow = window.innerWidth < 1024 ? "hidden" : "";

    // Show loading in panel
    document.getElementById("orderDetailContent").innerHTML = `
        <div class="flex justify-center items-center h-full">
            <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    `;

    fetch(`/kasir/orders/${orderId}`)
        .then((r) => r.json())
        .then((order) => {
            renderOrderDetail(order);
            currentOrderDetail = order;
        })
        .catch(() => {
            document.getElementById("orderDetailContent").innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-red-500">
                    <p class="text-sm">Gagal memuat detail</p>
                </div>
            `;
        });
}

function renderOrderDetail(order) {
    const badge = getStatusBadge(order.status);
    const color = getAvatarColor(order.cashier_name);
    const initials = getInitials(order.cashier_name);
    const subtotal = order.items.reduce((sum, item) => sum + item.subtotal, 0);
    const tax = subtotal * 0.1;

    let itemsHtml = "";
    order.items.forEach((item) => {
        itemsHtml += `
            <div class="flex items-center justify-between p-2.5 lg:p-3 bg-gray-50 rounded-xl">
                <div class="flex items-center">
                    <div class="w-8 h-8 lg:w-10 lg:h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-2 lg:mr-3">
                        <span class="text-base lg:text-lg">📦</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 text-xs lg:text-sm">${item.name}</p>
                        <p class="text-[10px] lg:text-xs text-gray-500">${item.category} · Qty: ${item.qty}</p>
                        ${item.note ? `<p class="text-[10px] text-gray-400 italic">${item.note}</p>` : ""}
                    </div>
                </div>
                <p class="font-semibold text-gray-900 text-xs lg:text-sm">${ordersFormatCurrency(item.subtotal)}</p>
            </div>
        `;
    });

    console.log("ORDER DEBUG = = = =", order);

    const html = `
        <!-- Header -->
        <div class="p-3 lg:p-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-base lg:text-lg text-gray-900">#${order.order_number}</h2>
                <p class="text-xs lg:text-sm text-gray-500">${order.created_at} at ${order.created_time}</p>
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
                <span class="px-2.5 lg:px-3 py-1 lg:py-1.5 ${badge.bg} ${badge.text} rounded-full text-xs lg:text-sm font-medium">${badge.label}</span>
            </div>
        </div>

        <!-- Cashier Info -->
        <div class="p-3 lg:p-4 border-b border-gray-100">
            <h3 class="text-xs lg:text-sm font-medium text-gray-500 mb-2 lg:mb-3">Kasir</h3>
            <div class="flex items-center">
                <div class="w-10 h-10 lg:w-12 lg:h-12 ${color.bg} rounded-full flex items-center justify-center mr-3">
                    <span class="text-base lg:text-lg font-medium ${color.text}">${initials}</span>
                </div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm lg:text-base">${order.cashier_name}</p>
                    <p class="text-xs lg:text-sm text-gray-500">${getOrderTypeLabel(order.order_type)}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="flex-1 overflow-y-auto p-3 lg:p-4 scrollbar-hide">
            <h3 class="text-xs lg:text-sm font-medium text-gray-500 mb-2 lg:mb-3">Order Items (${order.items.length})</h3>
            <div class="space-y-2 lg:space-y-3">
                ${itemsHtml}
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="border-t border-gray-200 p-3 lg:p-4 bg-gray-50">
            <h3 class="text-xs lg:text-sm font-medium text-gray-500 mb-2 lg:mb-3">Payment Summary</h3>
            <div class="space-y-1.5 lg:space-y-2">
                <div class="flex justify-between text-xs lg:text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="text-gray-900">${ordersFormatCurrency(subtotal)}</span>
                </div>
                <div class="flex justify-between text-xs lg:text-sm">
                    <span class="text-gray-600">Tax (10%)</span>
                    <span class="text-gray-900">${ordersFormatCurrency(tax)}</span>
                </div>
                <div class="border-t border-gray-200 pt-2 mt-2">
                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-900 text-sm lg:text-base">Total</span>
                        <span class="font-bold text-lg lg:text-xl text-indigo-600">${ordersFormatCurrency(parseFloat(order.total))}</span>
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
                            <p class="font-medium text-gray-900 text-xs lg:text-sm">${getPaymentLabel(order.payment_method)}</p>
                            <p class="text-[10px] lg:text-xs text-gray-500">${order.status === "completed" ? "Paid in full" : "Draft - belum dibayar"}</p>
                        </div>
                    </div>
                    <span class="${order.status === "completed" ? "text-green-600" : "text-orange-600"} text-xs lg:text-sm font-medium">
                        ${order.status === "completed" ? "✓ Paid" : "⏸ Hold"}
                    </span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="p-3 lg:p-4 border-t border-gray-200 space-y-2">
            ${
                order.status === "draft"
                    ? `
                <button onclick="resumeOrder()" class="w-full px-3 lg:px-4 py-2 lg:py-2.5 bg-indigo-600 text-white rounded-xl text-xs lg:text-sm font-medium hover:bg-indigo-700 transition flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 lg:w-4 lg:h-4 mr-1.5 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-.274A1 1 0 0010.52 12l.27 3.397a1 1 0 001.106 1.035l3.197-.274a1 1 0 00.894-1.036l-.27-3.397a1 1 0 00-1.036-.557z"/>
                    </svg>
                    Resume di POS
                </button>
            `
                    : `
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
            `
            }
        </div>
    `;

    document.getElementById("orderDetailContent").innerHTML = html;
}

function closeOrderDetail() {
    const panel = document.getElementById("orderDetailPanel");
    panel.classList.add("hidden");
    document.body.style.overflow = "";
}

function resumeOrder() {
    if (!currentOrderDetail || currentOrderDetail.status !== "draft") {
        alert("Hanya draft orders yang bisa di-resume");
        return;
    }

    // Format items untuk cart (extract hanya field yang dibutuhkan)
    const cartItems = currentOrderDetail.items.map((item) => ({
        product_id: item.product_id,
        name: item.name,
        price: item.price,
        qty: item.qty,
        category: item.category,
        note: item.note || null,
    }));

    // Simpan draft data ke localStorage
    const draftData = {
        items: cartItems,
        currentDraftId: currentOrderDetail.id,
        selectedOrderType: currentOrderDetail.order_type,
        selectedPaymentMethod: currentOrderDetail.payment_method,
    };

    localStorage.setItem("pos_resume_cart", JSON.stringify(draftData));

    // Close detail panel dan redirect ke POS
    closeOrderDetail();
    window.location.href = `/kasir/pos`;
}

// INIT

document.addEventListener("DOMContentLoaded", function () {
    const config = document.getElementById("ordersConfig");
    if (config) {
        routeOrdersData = config.dataset.routeOrdersData || "";
        ordersCSRFToken = config.dataset.csrfToken || "";
    }

    // Init search
    const searchInput = document.getElementById("ordersSearchInput");
    if (searchInput) {
        searchInput.addEventListener("input", function (e) {
            clearTimeout(ordersSearchTimeout);
            ordersSearchTimeout = setTimeout(() => {
                ordersSearchQuery = e.target.value;
                loadOrders(true);
            }, 300);
        });
    }

    // Load initial orders
    loadOrders(true);
});
