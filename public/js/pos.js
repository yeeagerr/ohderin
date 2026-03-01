let cart = [];
let currentPage = 1;
let currentCategory = "all";
let currentSearch = "";
let selectedOrderType = "dine_in";
let selectedPaymentMethod = "cash";
let currentDraftId = null; //BUAT NANDAIN KALAU INITUH DARI DRAFT!!
const TAX_RATE = 0.1;

let routeProducts = "";
let routeCheckout = "";
let routeHold = "";
let routeDrafts = "";
let csrfToken = "";

const CART_KEY = "pos_cart";
const RESUME_KEY = "pos_resume_cart";
const QUEUE_KEY = "pos_offline_queue";

function saveCart() {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
}

function saveDraft() {
    localStorage.setItem(RESUME_KEY, JSON.stringify(cart));
}

function loadCartWithPriority() {
    const draft = localStorage.getItem(RESUME_KEY);
    const cart = localStorage.getItem(CART_KEY);

    if (draft) {
        const draftData = JSON.parse(draft);
        console.log("DRAFT DATA = = ", draftData);

        // Handle format dari orders.js (dengan struktur lengkap)
        if (draftData.items && Array.isArray(draftData.items)) {
            // Set global state dari draft
            if (draftData.currentDraftId) {
                currentDraftId = draftData.currentDraftId;
            }
            if (draftData.selectedOrderType) {
                selectedOrderType = draftData.selectedOrderType;
            }
            if (draftData.selectedPaymentMethod) {
                selectedPaymentMethod = draftData.selectedPaymentMethod;
            }
            return draftData.items;
        }

        // Handle format lama (hanya items array)
        if (Array.isArray(draftData)) {
            return draftData;
        }

        // Handle format dari resumeDraft modal (items property)
        if (draftData && draftData.items) {
            return draftData.items;
        }
    }

    if (cart) {
        return JSON.parse(cart);
    }

    return [];
}

function getQueue() {
    const q = localStorage.getItem(QUEUE_KEY);
    return q ? JSON.parse(q) : [];
}

function pushToQueue(payload) {
    const queue = getQueue();
    queue.push({ payload, timestamp: Date.now() });
    localStorage.setItem(QUEUE_KEY, JSON.stringify(queue));
}

function showToast(msg) {
    let el = document.getElementById("posToast");
    if (!el) {
        el = document.createElement("div");
        el.id = "posToast";
        el.style.cssText =
            "position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:#1e293b;color:#fff;padding:10px 20px;border-radius:10px;font-size:13px;z-index:9999;transition:opacity .3s;";
        document.body.appendChild(el);
    }
    el.textContent = msg;
    el.style.opacity = "1";
    clearTimeout(el._t);
    el._t = setTimeout(() => (el.style.opacity = "0"), 4000);
}

function updateOfflineBanner() {
    let el = document.getElementById("offlineBanner");
    if (!el) {
        el = document.createElement("div");
        el.id = "offlineBanner";
        el.style.cssText =
            "position:fixed;top:0;left:0;right:0;text-align:center;padding:6px;font-size:13px;font-weight:600;z-index:9999;display:none;";
        document.body.appendChild(el);
    }
}

async function syncQueue() {
    const queue = getQueue();
    if (!queue.length) return;

    const failed = [];
    for (const entry of queue) {
        try {
            const res = await fetch(routeCheckout, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(entry.payload),
            });
            const data = await res.json();
            if (!data.success) failed.push(entry);
        } catch {
            failed.push(entry);
        }
    }

    localStorage.setItem(QUEUE_KEY, JSON.stringify(failed));
    const synced = queue.length - failed.length;
    if (synced > 0)
        showToast(`${synced} transaksi offline berhasil disinkronkan`);
    if (failed.length > 0)
        showToast(`${failed.length} transaksi gagal disinkronkan`);
}

window.addEventListener("online", async () => {
    updateOfflineBanner();
    showToast("Kembali online, menyinkronkan...");
    await syncQueue();
    updateOfflineBanner();
});

window.addEventListener("offline", () => {
    updateOfflineBanner();
    showToast("Offline. Transaksi akan disimpan & dikirim saat online.");
});

let cartOpen = true;

function toggleCart() {
    const sidebar = document.getElementById("cartSidebar");
    const floatingBtn = document.getElementById("cartToggleFloating");
    const toggleIcon = document.getElementById("cartToggleIcon");

    cartOpen = !cartOpen;

    if (cartOpen) {
        sidebar.classList.remove("cart-closed");
        sidebar.classList.add("cart-open");
        floatingBtn.style.display = "none";
        if (toggleIcon) toggleIcon.style.transform = "rotate(0deg)";
    } else {
        sidebar.classList.remove("cart-open");
        sidebar.classList.add("cart-closed");
        floatingBtn.style.display = "flex";
        if (toggleIcon) toggleIcon.style.transform = "rotate(180deg)";
    }

    updateFloatingBadge();
}

function updateFloatingBadge() {
    const badge = document.getElementById("cartBadge");
    const { itemCount } = calculateTotals();

    if (itemCount > 0) {
        badge.textContent = itemCount > 99 ? "99+" : itemCount;
        badge.style.display = "flex";
    } else {
        badge.style.display = "none";
    }
}

function formatCurrency(amount) {
    return "Rp " + new Intl.NumberFormat("id-ID").format(amount);
}

function addToCart(productId, name, price, category) {
    const existingItem = cart.find((item) => item.product_id === productId);

    if (existingItem) {
        existingItem.qty++;
    } else {
        cart.push({
            product_id: productId,
            name,
            price,
            qty: 1,
            category,
            note: null,
        });
    }

    saveCart();
    renderCart();
    updateFloatingBadge();
}

function updateQuantity(productId, change) {
    const item = cart.find((item) => item.product_id === productId);
    if (item) {
        item.qty += change;
        if (item.qty <= 0) {
            removeFromCart(productId);
        } else {
            saveCart();
            renderCart();
            updateFloatingBadge();
        }
    }
}

function removeFromCart(productId) {
    cart = cart.filter((item) => item.product_id !== productId);
    saveCart();
    renderCart();
    updateFloatingBadge();
}

function clearCart() {
    cart = [];
    currentDraftId = null;
    saveCart();
    renderCart();
    updateFloatingBadge();
}

function calculateTotals() {
    const subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
    const tax = subtotal * TAX_RATE;
    const total = subtotal + tax;
    const itemCount = cart.reduce((sum, item) => sum + item.qty, 0);

    return { subtotal, tax, total, itemCount };
}

function renderCart() {
    const cartContainer = document.getElementById("cartItems");
    const { subtotal, tax, total, itemCount } = calculateTotals();
    const holdBtn = document.getElementById("holdBtn");
    console.log("DEBUG CART = = = ", cart);

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
        document.getElementById("checkoutBtn").disabled = true;
        if (holdBtn) holdBtn.disabled = true;
    } else {
        let html = "";
        cart.forEach((item) => {
            html += `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-lg">📦</span>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-medium text-gray-900 truncate" title="${item.name}">${item?.name?.substring(0, 15) || item.product_name}${item?.name?.length || item.product_name > 15 ? "..." : ""}</h4>
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
        document.getElementById("checkoutBtn").disabled = false;
        if (holdBtn) holdBtn.disabled = false;
    }

    document.getElementById("cartItemCount").textContent =
        `(${itemCount} items)`;
    document.getElementById("cartSubtotal").textContent =
        formatCurrency(subtotal);
    document.getElementById("cartTax").textContent = formatCurrency(tax);
    document.getElementById("cartTotal").textContent = formatCurrency(total);

    // Update order number display if resuming draft
    const orderNumEl = document.getElementById("orderNumber");
    if (orderNumEl && currentDraftId) {
        orderNumEl.textContent = "# Draft";
        orderNumEl.classList.add("text-orange-600");
    } else if (orderNumEl) {
        orderNumEl.textContent = "# 1";
        orderNumEl.classList.remove("text-orange-600");
    }
}

let searchTimeout;

function initSearch() {
    const searchInput = document.getElementById("searchInput");
    if (!searchInput) return;

    searchInput.addEventListener("input", function (e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentSearch = e.target.value;
            currentPage = 1;
            loadProducts(true);
        }, 300);
    });
}

function filterByCategory(category) {
    currentCategory = category;
    currentPage = 1;

    document.querySelectorAll(".category-tab").forEach((tab) => {
        if (tab.dataset.category === category) {
            tab.classList.add("bg-indigo-600", "text-white");
            tab.classList.remove("text-gray-600", "hover:bg-gray-100");
        } else {
            tab.classList.remove("bg-indigo-600", "text-white");
            tab.classList.add("text-gray-600", "hover:bg-gray-100");
        }
    });

    loadProducts(true);
}

function loadProducts(reset = false) {
    const grid = document.getElementById("productsGrid");
    const loading = document.getElementById("loadingIndicator");
    const loadMore = document.getElementById("loadMoreContainer");

    if (reset) {
        grid.innerHTML = "";
        currentPage = 1;
    }

    loading.classList.remove("hidden");
    if (loadMore) loadMore.classList.add("hidden");

    const params = new URLSearchParams({
        page: currentPage,
        search: currentSearch,
        category: currentCategory,
    });

    fetch(`${routeProducts}?${params}`)
        .then((response) => response.json())
        .then((data) => {
            loading.classList.add("hidden");

            data.products.forEach((product) => {
                const div = document.createElement("div");
                div.className =
                    "bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group";
                div.onclick = () =>
                    addToCart(
                        product.id,
                        product.name,
                        parseFloat(product.price),
                        product.category,
                    );
                div.innerHTML = `
                    <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mb-4">
                        <span class="text-4xl">📦</span>
                    </div>
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur rounded-full px-2.5 py-1 text-xs font-semibold text-gray-700 shadow">
                        ${product.category}
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1 truncate" title="${product.name}">${product.name.substring(0, 20)}${product.name.length > 20 ? "..." : ""}</h3>
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
                let loadMoreEl = document.getElementById("loadMoreContainer");
                if (!loadMoreEl) {
                    loadMoreEl = document.createElement("div");
                    loadMoreEl.id = "loadMoreContainer";
                    loadMoreEl.className = "flex justify-center py-4";
                    loadMoreEl.innerHTML = `
                        <button onclick="loadMoreProducts()" class="px-6 py-2.5 bg-indigo-100 text-indigo-600 rounded-xl hover:bg-indigo-200 font-medium transition">
                            Load More Products
                        </button>
                    `;
                    document
                        .getElementById("productsContainer")
                        .appendChild(loadMoreEl);
                }
                loadMoreEl.classList.remove("hidden");
            }
        })
        .catch(() => {
            loading.classList.add("hidden");
            if (!grid.children.length) {
                grid.innerHTML = `<div class="col-span-full text-center text-gray-400 py-10">Tidak dapat memuat produk saat offline</div>`;
            }
        });
}

function loadMoreProducts() {
    currentPage++;
    loadProducts(false);
}

function showPaymentModal() {
    if (cart.length === 0) return;

    const { total } = calculateTotals();
    document.getElementById("modalTotal").textContent = formatCurrency(total);
    document.getElementById("paymentModal").classList.remove("hidden");
}

function hidePaymentModal() {
    document.getElementById("paymentModal").classList.add("hidden");
}

function selectOrderType(type) {
    selectedOrderType = type;
    document.querySelectorAll(".order-type-btn").forEach((btn) => {
        if (btn.dataset.type === type) {
            btn.classList.add(
                "border-indigo-600",
                "bg-indigo-50",
                "text-indigo-600",
            );
            btn.classList.remove("border-gray-300");
        } else {
            btn.classList.remove(
                "border-indigo-600",
                "bg-indigo-50",
                "text-indigo-600",
            );
            btn.classList.add("border-gray-300");
        }
    });
}

function selectPaymentMethod(method) {
    selectedPaymentMethod = method;
    document.querySelectorAll(".payment-method-btn").forEach((btn) => {
        if (btn.dataset.method === method) {
            btn.classList.add(
                "border-indigo-600",
                "bg-indigo-50",
                "text-indigo-600",
            );
            btn.classList.remove("border-gray-300");
        } else {
            btn.classList.remove(
                "border-indigo-600",
                "bg-indigo-50",
                "text-indigo-600",
            );
            btn.classList.add("border-gray-300");
        }
    });
}

function processCheckout() {
    const btn = document.getElementById("processPaymentBtn");
    btn.disabled = true;
    btn.textContent = "Processing...";

    const { total } = calculateTotals();
    const payload = {
        items: cart,
        order_type: selectedOrderType,
        payment_method: selectedPaymentMethod,
        total: total,
        draft_id: currentDraftId, // Include draft_id if resuming
    };

    if (!navigator.onLine) {
        pushToQueue(payload);
        updateOfflineBanner();
        btn.disabled = false;
        btn.textContent = "Proses Pembayaran";
        hidePaymentModal();
        showToast("Transaksi disimpan, akan dikirim saat online");
        document.getElementById("successOrderNumber").textContent =
            "Tersimpan (Offline)";
        document.getElementById("successModal").classList.remove("hidden");
        return;
    }
    console.log("PAYLOAD = = ", payload);
    fetch(routeCheckout, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify(payload),
    })
        .then((response) => response.json())
        .then((data) => {
            btn.disabled = false;
            btn.textContent = "Proses Pembayaran";

            if (data.success) {
                hidePaymentModal();
                localStorage.removeItem("pos_resume_cart");
                localStorage.removeItem("pos_cart");
                document.getElementById("successOrderNumber").textContent =
                    "Order " + data.order_number;
                document
                    .getElementById("successModal")
                    .classList.remove("hidden");
            } else {
                alert(data.message || "Terjadi kesalahan");
            }
        })
        .catch(() => {
            pushToQueue(payload);
            updateOfflineBanner();
            btn.disabled = false;
            btn.textContent = "Proses Pembayaran";
            hidePaymentModal();
            showToast("Gagal kirim, transaksi disimpan & akan disync otomatis");
            document.getElementById("successOrderNumber").textContent =
                "Tersimpan (Offline)";
            document.getElementById("successModal").classList.remove("hidden");
        });
}

function hideSuccessModal() {
    document.getElementById("successModal").classList.add("hidden");
    cart = [];
    currentDraftId = null;
    localStorage.removeItem("pos_resume_cart");
    localStorage.removeItem("pos_cart");
    clearCart();
    loadDraftCount(); // Refresh draft count
}

// HOLD / DRAFT FUNCTIONS

function holdOrder() {
    if (cart.length === 0) return;

    const holdBtn = document.getElementById("holdBtn");
    holdBtn.disabled = true;

    const { total } = calculateTotals();
    const payload = {
        items: cart,
        order_type: selectedOrderType,
        payment_method: selectedPaymentMethod,
        total: total,
        draft_id: currentDraftId, // Include draft_id if updating existing draft
    };
    console.log("Hold Payload = = ", payload);

    fetch(routeHold, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify(payload),
    })
        .then((response) => response.json())
        .then((data) => {
            holdBtn.disabled = false;
            console.log("Hold response", data);
            if (data.success) {
                showToast(`Order ${data.order_number} berhasil di-hold!`);
                // Update currentDraftId if this was a new draft creation
                if (!currentDraftId) {
                    currentDraftId = data.sale_id;
                }
                clearCart();
                loadDraftCount();
            } else {
                console.log("Hold error", data.message);
                showToast(data.message || "Gagal hold order");
            }
        })
        .catch((err) => {
            console.log("Hold failed", err);
            holdBtn.disabled = false;
            showToast("Gagal hold order. Coba lagi.");
        });
}

// function loadDraftCount() {
//     fetch(routeDrafts)
//         .then(r => r.json())
//         .then(data => {
//             const badge = document.getElementById('draftBadge');
//             if (badge) {
//                 if (data.count > 0) {
//                     badge.textContent = data.count;
//                     badge.style.display = 'flex';
//                 } else {
//                     badge.style.display = 'none';
//                 }
//             }
//         })
//         .catch(() => { });
// }

function loadDraftCount() {
    fetch(routeDrafts)
        .then((r) => r.json())
        .then((data) => {
            const badge = document.getElementById("draftBadge");
            if (badge) {
                const count = data.drafts ? data.drafts.length : 0;
                if (count > 0) {
                    badge.textContent = count > 99 ? "99+" : count;
                    badge.style.display = "flex";
                } else {
                    badge.style.display = "none";
                }
            }
        })
        .catch(() => {});
}

function showDraftsModal() {
    document.getElementById("draftsModal").classList.remove("hidden");
    loadDraftsList();
}

function hideDraftsModal() {
    document.getElementById("draftsModal").classList.add("hidden");
}

function loadDraftsList() {
    const container = document.getElementById("draftsListContainer");
    container.innerHTML =
        '<div class="flex justify-center py-8"><svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';

    fetch(routeDrafts)
        .then((r) => r.json())
        .then((data) => {
            if (!data.drafts || data.drafts.length === 0) {
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                        <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm">Belum ada draft</p>
                    </div>
                `;
                return;
            }

            let html = "";
            data.drafts.forEach((draft) => {
                html += `
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 hover:border-indigo-300 transition">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <span class="font-semibold text-gray-900 text-sm">${draft.order_number}</span>
                                <span class="ml-2 px-2 py-0.5 bg-orange-100 text-orange-700 rounded-full text-xs font-medium">Draft</span>
                            </div>
                            <span class="text-xs text-gray-500">${draft.created_at}</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-1">${draft.items_count} item · ${draft.items_summary}</p>
                        <p class="font-bold text-indigo-600 mb-3">${formatCurrency(parseFloat(draft.total))}</p>
                        <div class="flex space-x-2">
                            <button onclick="resumeDraft(${draft.id})" class="flex-1 px-3 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                                Resume
                            </button>
                            <button onclick="deleteDraft(${draft.id})" class="px-3 py-2 border border-red-200 text-red-600 rounded-lg text-sm font-medium hover:bg-red-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        })
        .catch(() => {
            container.innerHTML =
                '<div class="text-center text-red-500 py-4 text-sm">Gagal memuat drafts</div>';
        });
}

function resumeDraft(draftId) {
    fetch(`${routeDrafts}/${draftId}`)
        .then((r) => r.json())
        .then((data) => {
            if (data.success) {
                // Clear current cart and load draft items
                cart = data.items;
                currentDraftId = data.draft_id;
                selectedOrderType = data.order_type || "dine_in";
                selectedPaymentMethod = data.payment_method || "cash";
                saveDraft();
                renderCart();
                selectOrderType(selectedOrderType);
                selectPaymentMethod(selectedPaymentMethod);
                updateFloatingBadge();
                hideDraftsModal();
                showToast(`Draft ${data.order_number} di-resume ke keranjang`);
            } else {
                showToast("Gagal resume draft");
            }
        })
        .catch(() => {
            showToast("Gagal resume draft");
        });
}

function deleteDraft(draftId) {
    if (!confirm("Hapus draft ini?")) return;

    fetch(`${routeDrafts}/${draftId}`, {
        method: "DELETE",
        headers: { "X-CSRF-TOKEN": csrfToken },
    })
        .then((r) => r.json())
        .then((data) => {
            if (data.success) {
                showToast("Draft berhasil dihapus");
                loadDraftsList();
                loadDraftCount();
            } else {
                showToast("Gagal menghapus draft");
            }
        })
        .catch(() => {
            showToast("Gagal menghapus draft");
        });
}

// =============================================
// INITIALIZATION
// =============================================

document.addEventListener("DOMContentLoaded", function () {
    const posConfig = document.getElementById("posConfig");
    if (posConfig) {
        routeProducts = posConfig.dataset.routeProducts || "";
        routeCheckout = posConfig.dataset.routeCheckout || "";
        routeHold = posConfig.dataset.routeHold || "";
        routeDrafts = posConfig.dataset.routeDrafts || "";
        csrfToken = posConfig.dataset.csrfToken || "";
    }

    // Load cart with priority (draft from orders > cart > empty)
    cart = loadCartWithPriority();

    // If draft was loaded, update UI state from draft
    if (
        currentDraftId ||
        (localStorage.getItem(RESUME_KEY) &&
            JSON.parse(localStorage.getItem(RESUME_KEY)).currentDraftId)
    ) {
        const draftData = JSON.parse(localStorage.getItem(RESUME_KEY) || "{}");
        if (draftData.currentDraftId) {
            currentDraftId = draftData.currentDraftId;
        }
        if (draftData.selectedOrderType) {
            selectedOrderType = draftData.selectedOrderType;
        }
        if (draftData.selectedPaymentMethod) {
            selectedPaymentMethod = draftData.selectedPaymentMethod;
        }
    }

    initSearch();
    renderCart();
    updateFloatingBadge();
    updateOfflineBanner();
    loadDraftCount();

    // Make draft badge clickable to show drafts panel
    const holdBtn = document.getElementById("holdBtn");
    if (holdBtn) {
        const draftBadge = document.getElementById("draftBadge");
        if (draftBadge) {
            draftBadge.addEventListener("click", function (e) {
                e.stopPropagation();
                showDraftsModal();
            });
            draftBadge.style.cursor = "pointer";
        }
    }

    if (navigator.onLine && getQueue().length > 0) {
        syncQueue().then(updateOfflineBanner);
    }
});
