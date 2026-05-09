let cart = [];
let currentPage = 1;
let currentCategory = "all";
let currentSearch = "";
let selectedOrderType = "dine_in";
let selectedPaymentMethod = "cash";
let selectedTableId = null;
let splitBillGroup = "";
let currentDraftId = null;
let modifiersList = [];
let productModifierMap = {};
let tablesList = [];
const TAX_RATE = 0.1;

let routeProducts = "";
let routeCheckout = "";
let routeHold = "";
let routeDrafts = "";
let routeRegisterStatus = "";
let csrfToken = "";
let registersList = [];
let activeRegisterSession = null;

const CART_KEY = "pos_cart";
const RESUME_KEY = "pos_resume_cart";
const QUEUE_KEY = "pos_offline_queue";

function updateRegisterInfoLabel() {
    const el = document.getElementById("activeRegisterInfo");
    if (!el) return;
    if (activeRegisterSession && activeRegisterSession.register_name) {
        el.innerHTML = `Session: <span class="font-semibold text-orange-600">${activeRegisterSession.register_name}</span>`;
        return;
    }
    el.innerHTML =
        '<span class="text-red-500 font-medium">Belum ada session kasir aktif</span>';
}

function showRegisterPicker() {
    const modal = document.getElementById("registerPickerModal");
    const list = document.getElementById("registerPickerList");
    if (!modal || !list) return;

    let html = "";
    registersList.forEach((register) => {
        html += `
            <div class="border border-gray-200 rounded-xl p-3 flex items-center justify-between">
                <div>
                    <p class="font-medium text-gray-900">${register.name}</p>
                    <p class="text-xs ${register.is_active ? "text-green-600" : "text-red-500"}">${register.is_active ? "Aktif" : "Nonaktif"}</p>
                </div>
                <button onclick="enterRegisterSession(${register.id}, '${String(
            register.name,
        ).replace(/'/g, "\\'")}')" class="px-3 py-2 bg-orange-500 text-white rounded-lg text-sm hover:bg-orange-600">
                    Masuk Session
                </button>
            </div>
        `;
    });
    list.innerHTML =
        html ||
        '<div class="text-sm text-gray-500">Belum ada register. Buat dulu di menu Kasir.</div>';
    modal.classList.remove("hidden");
}

function hideRegisterPicker() {
    const modal = document.getElementById("registerPickerModal");
    if (modal) modal.classList.add("hidden");
}

function enterRegisterSession(registerId, registerName) {
    fetch(`/kasir/registers/${registerId}/enter`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
    })
        .then((r) => r.json())
        .then((data) => {
            if (!data.success) return alert(data.message || "Gagal masuk session");
            if (data.needs_open_register) {
                hideRegisterPicker();
                document.getElementById("openRegisterIdPos").value = registerId;
                document.getElementById(
                    "openRegisterNamePos",
                ).textContent = `Kasir: ${registerName}`;
                document
                    .getElementById("openRegisterModalPos")
                    .classList.remove("hidden");
                return;
            }
            window.location.reload();
        })
        .catch(() => alert("Gagal masuk session"));
}

function hideOpenRegisterPos() {
    const modal = document.getElementById("openRegisterModalPos");
    if (modal) modal.classList.add("hidden");
}

function submitOpenRegisterPos() {
    const registerId = document.getElementById("openRegisterIdPos").value;
    const openingCash = parseFloat(
        document.getElementById("openingCashInputPos").value || 0,
    );
    const openingNote = document.getElementById("openingNoteInputPos").value || "";
    fetch(`/kasir/registers/${registerId}/open`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({
            opening_cash: openingCash,
            opening_note: openingNote,
        }),
    })
        .then((r) => r.json())
        .then((data) => {
            if (!data.success) return alert(data.message || "Gagal open register");
            window.location.reload();
        })
        .catch(() => alert("Gagal open register"));
}

function showCloseRegisterFromPos(sessionId, registerName) {
    document.getElementById("closeSessionIdPos").value = sessionId;
    document.getElementById(
        "closeRegisterNamePos",
    ).textContent = `Kasir: ${registerName}`;
    document.getElementById("closeRegisterModalPos").classList.remove("hidden");
}

function hideCloseRegisterFromPos() {
    document.getElementById("closeRegisterModalPos").classList.add("hidden");
}

function submitCloseRegisterFromPos() {
    const sessionId = document.getElementById("closeSessionIdPos").value;
    const closingCash = parseFloat(
        document.getElementById("closingCashInputPos").value || 0,
    );
    const closingNote = document.getElementById("closingNoteInputPos").value || "";
    fetch(`/kasir/register-sessions/${sessionId}/close`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({
            closing_cash: closingCash,
            closing_note: closingNote,
        }),
    })
        .then((r) => r.json())
        .then((data) => {
            if (!data.success) return alert(data.message || "Gagal close register");
            alert("Register berhasil ditutup");
            window.location.reload();
        })
        .catch(() => alert("Gagal close register"));
}

function saveCart() {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
    localStorage.setItem(
        RESUME_KEY,
        JSON.stringify({
            items: cart,
            currentDraftId,
            selectedOrderType,
            selectedPaymentMethod,
            table_id: selectedTableId,
            split_bill_group: splitBillGroup,
        }),
    );
}

function setTableId(value) {
    selectedTableId = value ? parseInt(value, 10) : null;
    saveCart();
}

function setSplitBillGroup(value) {
    splitBillGroup = value || "";
    saveCart();
}

function getModifierAdjustment(item) {
    if (!Array.isArray(item.modifiers)) return 0;
    return item.modifiers.reduce((sum, modifier) => {
        if (!modifier.modifier_id) return sum;
        const selected = getModifierById(modifier.modifier_id) || modifier;
        const quantity = parseInt(modifier.quantity, 10) || 1;
        return sum + (parseFloat(selected.price_adjustment) || 0) * quantity;
    }, 0);
}

function getModifierById(modifierId) {
    const id = parseInt(modifierId, 10);
    return modifiersList.find((modifier) => parseInt(modifier.id, 10) === id);
}

function normalizeModifier(modifier) {
    const modifierId = modifier?.modifier_id
        ? parseInt(modifier.modifier_id, 10)
        : null;
    const selected = modifierId ? getModifierById(modifierId) : null;
    const quantity = parseInt(modifier?.quantity, 10) || 1;

    return {
        modifier_id: modifierId,
        quantity: quantity > 0 ? quantity : 1,
        name: modifier?.name || selected?.name || null,
        price_adjustment:
            parseFloat(modifier?.price_adjustment ?? selected?.price_adjustment) || 0,
    };
}

function getModifierPayload(modifiers) {
    if (!Array.isArray(modifiers)) return [];
    return modifiers
        .map(normalizeModifier)
        .filter((modifier) => modifier.modifier_id)
        .map((modifier) => ({
            modifier_id: modifier.modifier_id,
            quantity: modifier.quantity,
        }));
}

function getItemSubtotal(item) {
    return ((parseFloat(item.price) || 0) + getModifierAdjustment(item)) * (parseInt(item.qty, 10) || 1);
}

function buildSalePayload(extra = {}) {
    const items = cart.map((item) => ({
        product_id: item.product_id,
        qty: parseInt(item.qty, 10) || 1,
        price: parseFloat(item.price) || 0,
        note: item.note || null,
        modifiers: getModifierPayload(item.modifiers),
    }));

    return {
        items,
        order_type: selectedOrderType,
        payment_method: selectedPaymentMethod,
        table_id: selectedTableId,
        split_bill_group: splitBillGroup,
        ...extra,
    };
}

function rememberProductModifiers(product) {
    const modifiers = Array.isArray(product.modifiers) ? product.modifiers : [];
    productModifierMap[product.id] = modifiers;
    return modifiers;
}

function getAllowedModifiers(item) {
    const baseModifiers = Array.isArray(item.allowed_modifiers)
        ? item.allowed_modifiers
        : productModifierMap[item.product_id] || [];
    const selectedIds = Array.isArray(item.modifiers)
        ? item.modifiers.map((modifier) => modifier.modifier_id).filter(Boolean)
        : [];
    const selectedExtras = selectedIds
        .map((modifierId) => getModifierById(modifierId))
        .filter(
            (modifier) =>
                modifier &&
                !baseModifiers.some(
                    (baseModifier) =>
                        parseInt(baseModifier.id, 10) ===
                        parseInt(modifier.id, 10),
                ),
        );

    return [...baseModifiers, ...selectedExtras];
}

function hydrateCartItems(items) {
    if (!Array.isArray(items)) return [];
    return items.map((item) => {
        const productModifiers = productModifierMap[item.product_id] || [];
        return {
            ...item,
            price: parseFloat(item.price) || 0,
            qty: parseInt(item.qty) || 1,
            note: item.note || null,
            modifiers: Array.isArray(item.modifiers)
                ? item.modifiers.map(normalizeModifier)
                : [],
            allowed_modifiers: Array.isArray(item.allowed_modifiers)
                ? item.allowed_modifiers
                : productModifiers,
        };
    });
}

function toggleSplitBill() {
    const input = document.getElementById("splitCountInput");
    if (!input) return;
    const count = splitBillCount > 1 ? 1 : 2;
    input.value = count;
    calculateSplitBill();
}

function renderSplitBillControls() {
    const controls = document.getElementById("splitBillControls");
    const toggleBtn = document.getElementById("toggleSplitBillBtn");
    if (!controls || !toggleBtn) return;
    if (splitBillCount > 1) {
        controls.classList.remove("hidden");
        toggleBtn.textContent = "Nonaktifkan";
    } else {
        controls.classList.add("hidden");
        toggleBtn.textContent = "Aktifkan";
    }
}

function addModifier(itemIndex) {
    if (!cart[itemIndex]) return;
    if (!getAllowedModifiers(cart[itemIndex]).length) return;
    if (!Array.isArray(cart[itemIndex].modifiers)) {
        cart[itemIndex].modifiers = [];
    }
    cart[itemIndex].modifiers.push({ modifier_id: null, quantity: 1 });
    saveCart();
    renderCart();
}

function removeModifier(itemIndex, modifierIndex) {
    if (!cart[itemIndex] || !Array.isArray(cart[itemIndex].modifiers)) return;
    cart[itemIndex].modifiers.splice(modifierIndex, 1);
    saveCart();
    renderCart();
}

function updateModifierSelection(itemIndex, modifierIndex, modifierId) {
    if (!cart[itemIndex] || !Array.isArray(cart[itemIndex].modifiers)) return;
    const id = modifierId ? parseInt(modifierId, 10) : null;
    const allowed = getAllowedModifiers(cart[itemIndex]);
    const isAllowed = allowed.some(
        (modifier) => parseInt(modifier.id, 10) === id,
    );
    const selectedModifier = id && isAllowed ? getModifierById(id) : null;
    cart[itemIndex].modifiers[modifierIndex] = {
        ...cart[itemIndex].modifiers[modifierIndex],
        modifier_id: selectedModifier ? id : null,
        name: selectedModifier?.name || null,
        price_adjustment: parseFloat(selectedModifier?.price_adjustment) || 0,
    };
    saveCart();
    renderCart();
}

function updateModifierQuantity(itemIndex, modifierIndex, quantity) {
    if (!cart[itemIndex] || !Array.isArray(cart[itemIndex].modifiers)) return;
    const qty = parseInt(quantity, 10) || 1;
    cart[itemIndex].modifiers[modifierIndex].quantity = qty > 0 ? qty : 1;
    saveCart();
    renderCart();
}

function loadCartWithPriority() {
    const draft = localStorage.getItem(RESUME_KEY);
    const storedCart = localStorage.getItem(CART_KEY);

    if (draft) {
        const draftData = JSON.parse(draft);

        if (draftData.items && Array.isArray(draftData.items)) {
            currentDraftId = draftData.currentDraftId || null;
            selectedOrderType = draftData.selectedOrderType || "dine_in";
            selectedPaymentMethod = draftData.selectedPaymentMethod || "cash";
            selectedTableId = draftData.table_id || null;
            splitBillGroup = draftData.split_bill_group || "";
            return hydrateCartItems(draftData.items);
        }

        if (Array.isArray(draftData)) {
            return hydrateCartItems(draftData);
        }

        if (draftData && draftData.items) {
            return hydrateCartItems(draftData.items);
        }
    }

    if (storedCart) {
        return hydrateCartItems(JSON.parse(storedCart));
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

function escapeAttribute(value) {
    return String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/"/g, "&quot;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}

function updateItemNote(itemIndex, value) {
    if (!cart[itemIndex]) return;
    const note = value.trim();
    cart[itemIndex].note = note ? note : null;
    saveCart();
}

function addToCart(productId, name, price, category, allowedModifiers = null) {
    const productModifiers = Array.isArray(allowedModifiers)
        ? allowedModifiers
        : productModifierMap[productId] || [];
    const existingItem = cart.find((item) => item.product_id === productId);

    if (existingItem && existingItem?.modifiers.length == 0) {
        existingItem.qty++;
        existingItem.allowed_modifiers = productModifiers;
    } else {
        let sequence = cart.length + 1;
        cart.push({
            id: sequence,
            product_id: productId,
            name,
            price,
            qty: 1,
            category,
            note: null,
            modifiers: [],
            allowed_modifiers: productModifiers,
        });
    }

    saveCart();
    renderCart();
    updateFloatingBadge();
}

function updateQuantity(id, change) {
    const item = cart.find((item) => item.id === id);
    if (item) {
        item.qty += change;
        if (item.qty <= 0) {
            removeFromCart(id);
        } else {
            saveCart();
            renderCart();
            updateFloatingBadge();
        }
    }
}

function removeFromCart(id) {
    cart = cart.filter((item) => item.id !== id);
    saveCart();
    renderCart();
    updateFloatingBadge();
}

function clearCart() {
    cart = [];
    currentDraftId = null;
    selectedTableId = null;
    splitBillGroup = "";
    localStorage.removeItem("pos_resume_cart");
    localStorage.removeItem("pos_cart");
    saveCart();
    renderCart();
    updateFloatingBadge();
}

function deleteCurrentDraft() {
    if (!currentDraftId) return;

    if (
        !confirm(
            "Hapus draft ini? Data akan hilang dan tidak bisa dikembalikan.",
        )
    )
        return;

    const deleteBtn = document.getElementById("deleteDraftBtn");
    if (deleteBtn) deleteBtn.disabled = true;

    fetch(`${routeDrafts}/${currentDraftId}`, {
        method: "DELETE",
        headers: { "X-CSRF-TOKEN": csrfToken },
    })
        .then((r) => r.json())
        .then((data) => {
            if (deleteBtn) deleteBtn.disabled = false;
            if (data.success) {
                showToast("Draft berhasil dihapus");
                cart = [];
                currentDraftId = null;
                selectedTableId = null;
                splitBillGroup = "";
                localStorage.removeItem("pos_resume_cart");
                localStorage.removeItem("pos_cart");
                saveCart();
                renderCart();
                updateFloatingBadge();
                loadDraftCount();
            } else {
                showToast(data.message || "Gagal menghapus draft");
            }
        })
        .catch(() => {
            if (deleteBtn) deleteBtn.disabled = false;
            showToast("Gagal menghapus draft");
        });
}

function calculateTotals() {
    const subtotal = cart.reduce((sum, item) => {
        return sum + getItemSubtotal(item);
    }, 0);
    const tax = subtotal * TAX_RATE;
    const total = subtotal + tax;
    const itemCount = cart.reduce((sum, item) => sum + item.qty, 0);

    return { subtotal, tax, total, itemCount };
}

function renderCart() {
    const cartContainer = document.getElementById("cartItems");
    const { subtotal, tax, total, itemCount } = calculateTotals();
    const holdBtn = document.getElementById("holdBtn");
    const hasActiveRegisterSession = !!(
        activeRegisterSession && activeRegisterSession.id
    );

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
        cart.forEach((item, index) => {
            const allowedModifiers = getAllowedModifiers(item);
            const modRows =
                Array.isArray(item.modifiers) && item.modifiers.length
                    ? item.modifiers
                          .map((modifier, modIndex) => {
                              const selectedModifier = getModifierById(
                                  modifier.modifier_id,
                              ) || modifier;
                              const modQuantity = parseInt(modifier.quantity, 10) || 1;
                              const pricePerUnit = parseFloat(selectedModifier?.price_adjustment) || 0;
                              const totalModifierPrice = pricePerUnit * modQuantity;
                              const priceInfo = selectedModifier
                                  ? totalModifierPrice >= 0
                                      ? ` +${formatCurrency(totalModifierPrice)} (${formatCurrency(pricePerUnit)} x ${modQuantity})`
                                      : ` ${formatCurrency(totalModifierPrice)} (${formatCurrency(pricePerUnit)} x ${modQuantity})`
                                  : "";
                              return `
                          <div class="mt-3 p-3 bg-white border border-gray-200 rounded-xl space-y-2">
                              <div class="grid grid-cols-3 gap-2">
                                  <select onchange="updateModifierSelection(${index}, ${modIndex}, this.value)" class="col-span-2 w-full border border-gray-300 rounded-xl px-3 py-2 text-sm">
                                      <option value="">Pilih modifier</option>
                                      ${allowedModifiers
                                          .map(
                                              (mod) => `
                                          <option value="${mod.id}" ${parseInt(modifier.modifier_id, 10) === parseInt(mod.id, 10) ? "selected" : ""}>
                                              ${mod.name}${(parseFloat(mod.price_adjustment) || 0) !== 0 ? ` ${parseFloat(mod.price_adjustment) > 0 ? "+" : ""}${formatCurrency(parseFloat(mod.price_adjustment) || 0)}` : ""}
                                          </option>
                                      `,
                                          )
                                          .join("")}
                                  </select>
                                  <button onclick="removeModifier(${index}, ${modIndex})" class="px-3 py-2 bg-red-100 text-red-600 rounded-xl text-sm">Hapus</button>
                              </div>
                              <div class="flex items-center gap-2">
                                  <label class="text-xs text-gray-600 whitespace-nowrap">Qty:</label>
                                  <input type="number" min="1" value="${modQuantity}" onchange="updateModifierQuantity(${index}, ${modIndex}, this.value)" class="w-16 border border-gray-300 rounded-lg px-2 py-1 text-sm" />
                              </div>

                              ${priceInfo ? `<p class="text-xs text-gray-500">Harga modifier:${priceInfo}</p>` : ""}
                          </div>
                      `;
                          })
                          .join("")
                    : "";

            html += `
                <div class="space-y-3 p-3 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 flex-1 min-w-0">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-lg">📦</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-medium text-gray-900 truncate" title="${item.name}">${item?.name?.substring(0, 15) || item.product_name}${item?.name?.length || item.product_name > 15 ? "..." : ""}</h4>
                                <div class="flex items-center space-x-2 mt-1">
                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                        <button onclick="updateQuantity(${item.id}, -1)" class="px-2 py-1 hover:bg-gray-100 text-gray-600">−</button>
                                        <span class="px-3 py-1 border-x border-gray-300 text-sm font-medium">${item.qty}</span>
                                        <button onclick="updateQuantity(${item.id}, 1)" class="px-2 py-1 hover:bg-gray-100 text-gray-600">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <p class="font-semibold text-gray-900">${formatCurrency(getItemSubtotal(item))}</p>
                            <button onclick="removeFromCart(${item.id})" class="p-1 text-red-500 hover:bg-red-50 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <textarea
                        class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm resize-none"
                        rows="2"
                        placeholder="Catatan item (opsional)"
                        onchange="updateItemNote(${index}, this.value)"
                    >${escapeAttribute(item.note)}</textarea>
                    ${modRows}
                    ${
                        allowedModifiers.length
                            ? `<button onclick="addModifier(${index})" class="w-full px-3 py-2 border border-dashed border-gray-300 rounded-xl text-sm text-gray-600 hover:bg-gray-100 transition">+ Tambah Modifier</button>`
                            : `<div class="w-full px-3 py-2 border border-dashed border-gray-200 rounded-xl text-sm text-gray-400 text-center">Tidak ada modifier untuk produk ini</div>`
                    }
                </div>
            `;
        });
        cartContainer.innerHTML = html;
        document.getElementById("checkoutBtn").disabled = !hasActiveRegisterSession;
        if (holdBtn) holdBtn.disabled = !hasActiveRegisterSession;
    }

    document.getElementById("cartItemCount").textContent =
        `(${itemCount} items)`;
    document.getElementById("cartSubtotal").textContent =
        formatCurrency(subtotal);
    document.getElementById("cartTax").textContent = formatCurrency(tax);
    document.getElementById("cartTotal").textContent = formatCurrency(total);

    const tableDisplay = document.getElementById("selectedTableDisplay");
    if (tableDisplay) {
        const tableName =
            tablesList.find((table) => table.id == selectedTableId)?.name ||
            "-";
        tableDisplay.textContent = tableName;
    }

    const splitBillDisplay = document.getElementById("splitBillGroupDisplay");
    const splitBillSummaryRow = document.getElementById("splitBillSummaryRow");
    if (splitBillDisplay) {
        splitBillDisplay.textContent = splitBillGroup || "-";
    }
    if (splitBillSummaryRow) {
        splitBillSummaryRow.style.display = splitBillGroup ? "flex" : "none";
    }

    const orderNumEl = document.getElementById("orderNumber");
    const deleteDraftBtn = document.getElementById("deleteDraftBtn");
    if (orderNumEl && currentDraftId) {
        orderNumEl.textContent = "# Draft";
        orderNumEl.classList.add("text-orange-600");
        if (deleteDraftBtn) deleteDraftBtn.classList.remove("hidden");
    } else if (orderNumEl) {
        orderNumEl.textContent = "# 1";
        orderNumEl.classList.remove("text-orange-600");
        if (deleteDraftBtn) deleteDraftBtn.classList.add("hidden");
    }

    // Automatically recalculate split bill display anytime UI changes
    if (typeof calculateSplitBill === "function") {
        calculateSplitBill();
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
            tab.classList.add("bg-orange-500", "text-white");
            tab.classList.remove("text-gray-600", "hover:bg-gray-100");
        } else {
            tab.classList.remove("bg-orange-500", "text-white");
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
                const productModifiers = rememberProductModifiers(product);
                const div = document.createElement("div");
                div.className =
                    "bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-200 p-4 cursor-pointer relative group";
                div.onclick = () =>
                    addToCart(
                        product.id,
                        product?.name,
                        parseFloat(product.price),
                        product.category,
                        productModifiers,
                    );
                div.innerHTML = `
                    <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center mb-4">
                        <span class="text-4xl">📦</span>
                    </div>
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur rounded-full px-2.5 py-1 text-xs font-semibold text-gray-700 shadow">
                        ${product.category.name}
                    </div>
                    ${
                        productModifiers.length
                            ? `<div class="absolute top-3 left-3 bg-orange-500 text-white rounded-full px-2.5 py-1 text-xs font-semibold shadow">${productModifiers.length} modifier</div>`
                            : ""
                    }
                    <h3 class="font-semibold text-gray-900 mb-1 truncate" title="${product?.name}">${product?.name?.substring(0, 20)}${product?.name?.length > 20 ? "..." : ""}</h3>
                    <p class="text-xl font-bold text-orange-500">${formatCurrency(parseFloat(product.price))}</p>
                    <button class="absolute bottom-4 right-4 p-2.5 bg-orange-500 text-white rounded-xl hover:bg-orange-600 opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg">
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
                        <button onclick="loadMoreProducts()" class="px-6 py-2.5 bg-orange-100 text-orange-500 rounded-xl hover:bg-orange-200 font-medium transition">
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
    if (!activeRegisterSession || !activeRegisterSession.id) {
        alert("Pilih kasir dan open register terlebih dahulu.");
        showRegisterPicker();
        return;
    }

    const { total } = calculateTotals();
    document.getElementById("modalTotal").textContent = formatCurrency(total);

    // Reset payment inputs
    const paidInput = document.getElementById("paidAmountInput");
    paidInput.value = total;
    document.getElementById("changeDisplay").textContent = formatCurrency(0);

    const splitCountInput = document.getElementById("splitCountInput");
    if (splitCountInput) {
        // Retrieve the number from splitBillGroup if it was previously saved
        if (
            splitBillGroup.startsWith("Split") &&
            splitBillGroup.includes("Orang")
        ) {
            const count = parseInt(splitBillGroup.match(/\d+/)[0]) || 1;
            splitCountInput.value = count;
        } else {
            splitCountInput.value = 1;
        }
    }

    goToStep(1);
    document.getElementById("paymentModal").classList.remove("hidden");

    // Calculate split bill right away in case it's carried over
    calculateSplitBill();

    // Add change listener if not already added
    if (!paidInput.dataset.listenerAdded) {
        paidInput.addEventListener("input", function () {
            const paid = parseFloat(this.value) || 0;
            const { total } = calculateTotals();
            const change = paid - total;
            const changeDisplay = document.getElementById("changeDisplay");

            changeDisplay.textContent = formatCurrency(change);

            if (change < 0) {
                changeDisplay.classList.add("text-red-600");
                changeDisplay.classList.remove("text-green-600");
            } else {
                changeDisplay.classList.add("text-green-600");
                changeDisplay.classList.remove("text-red-600");
            }
        });
        paidInput.dataset.listenerAdded = "true";
    }
}

let splitBillCount = 1;

function updateSplitCount(change) {
    const input = document.getElementById("splitCountInput");
    if (!input) return;
    let newValue = parseInt(input.value) + change;
    if (newValue < 1) newValue = 1;
    input.value = newValue;
    calculateSplitBill();
}

function calculateSplitBill() {
    const input = document.getElementById("splitCountInput");
    if (!input) return;
    let count = parseInt(input.value) || 1;
    if (count < 1) {
        count = 1;
        input.value = 1;
    }
    splitBillCount = count;

    // Save to splitBillGroup so it persists and sends to backend
    if (count > 1) {
        setSplitBillGroup(`Split ${count} Orang`);
    } else {
        setSplitBillGroup("");
    }

    const { total } = calculateTotals();
    const resultBox = document.getElementById("splitBillResult");
    const display = document.getElementById("splitPerPersonDisplay");

    if (count > 1) {
        const perPerson = Math.ceil(total / count);
        if (display) display.textContent = formatCurrency(perPerson);
        if (resultBox) resultBox.classList.remove("hidden");
    } else {
        if (resultBox) resultBox.classList.add("hidden");
    }
    renderSplitBillControls();
}

let lastTransaction = null;

function printReceipt() {
    if (!lastTransaction) return;

    const receiptWindow = window.open("", "_blank", "width=400,height=600");
    const itemsHtml = lastTransaction.items
        .map((item) => {
            const validModifiers = Array.isArray(item.modifiers)
                ? item.modifiers
                      .map(normalizeModifier)
                      .filter((modifier) => modifier.modifier_id)
                : [];
            const modifierLines = validModifiers
                .map((mod) => {
                    const adjustment = parseFloat(mod.price_adjustment) || 0;
                    const amount = adjustment * mod.quantity;
                    const priceText = adjustment
                        ? ` - ${formatCurrency(amount)}`
                        : "";
                    return `<p class="text-xs text-gray-600">- ${mod.name || "Modifier"} (qty: ${mod.quantity})${priceText}</p>`;
                })
                .join("");

            return `
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1 break-words">
                <p class="text-sm font-semibold text-xl leading-tight">${item.name}</p>
                <p class="text-sm font-semibold">${item.qty}x ${formatCurrency(item.price)}</p>
                ${modifierLines ? `
                <div class="mt-2 space-y-1">
                    ${modifierLines}
                </div>
                ` : ''}
            </div>
            <p class="text-sm text-xl font-[500] whitespace-nowrap flex-shrink-0">${formatCurrency(getItemSubtotal(item))}</p>
        </div>
    `;
        })
        .join("");

    const receiptHtml = `
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Receipt - ${lastTransaction.orderNumber}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            @media print {
                body { margin: 0; padding: 0; }
                .no-print { display: none; }
            }
        </style>
    </head>
    <body class="bg-gray-100">
        <div class="w-[21rem] mx-auto p-4 bg-white shadow-lg print:shadow-none print:w-full">
            <div class="flex items-center justify-center flex-col">
                <img src="/properties/logo_1.png" alt="OH DERIN Logo" class="w-20 h-20 mb-3 object-contain">
                <h1 class="font-bold text-3xl">OH DERIN</h1>
                <p class="text-sm font-semibold">Cafe & Restaurant</p>
            </div>

            <div class="flex flex-col gap-2 mt-8">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold">No. Invoice</p>
                    <p class="text-xs font-semibold">${lastTransaction.orderNumber}</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold">Tanggal</p>
                    <p class="text-xs font-semibold">${new Date().toLocaleString("id-ID")}</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold">Kasir</p>
                    <p class="text-xs font-semibold">${activeRegisterSession?.register_name || "Staff"}</p>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold">Metode Pembayaran</p>
                    <p class="text-xs font-semibold uppercase">${lastTransaction.paymentMethod}</p>
                </div>
            </div>

            <div class="border border-[#B9B9B9] w-[100%] my-4 rounded-2xl"></div>

            <div class="flex flex-col gap-4">
                ${itemsHtml}
            </div>

            <div class="border border-[#B9B9B9] w-[100%] my-4 rounded-2xl"></div>

            <div class="flex items-center justify-between">
                <p class="text-lg font-bold">TOTAL</p>
                <p class="text-lg font-bold">${formatCurrency(lastTransaction.total)}</p>
            </div>
            
            ${
                lastTransaction.splitBillGroup
                    ? `
            <div class="flex items-center justify-between mt-2">
                <p class="text-md font-semibold text-gray-600">${lastTransaction.splitBillGroup}</p>
                <p class="text-md font-bold">${formatCurrency(Math.ceil(lastTransaction.total / (parseInt(lastTransaction.splitBillGroup.match(/\\d+/)?.[0]) || 1)))}/org</p>
            </div>
            `
                    : ""
            }

            <div class="border border-[#B9B9B9] w-[100%] my-4 rounded-2xl"></div>

            <div class="flex items-center justify-between mb-2">
                <p class="text-lg font-semibold">Bayar</p>
                <p class="text-lg font-semibold">${formatCurrency(lastTransaction.paid)}</p>
            </div>

            <div class="flex items-center justify-between">
                <p class="text-lg font-semibold">Kembalian</p>
                <p class="text-lg font-semibold">${formatCurrency(lastTransaction.change)}</p>
            </div>
            
            <div class="mt-8 text-center text-xs text-gray-500">
                <p>Terima kasih atas kunjungan Anda</p>
                <p>Silahkan datang kembali!</p>
            </div>
        </div>
        <script>
            window.onload = function() {
                window.print();
                // window.close(); // Uncomment if you want it to close after print
            };
        </script>
    </body>
</html>
    `;

    receiptWindow.document.write(receiptHtml);
    receiptWindow.document.close();
}

function hidePaymentModal() {
    document.getElementById("paymentModal").classList.add("hidden");
}

function selectOrderType(type) {
    selectedOrderType = type;
    saveCart();
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
    saveCart();
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

    const paidValue =
        parseFloat(document.getElementById("paidAmountInput").value) || total;
    const changeValue = paidValue - total;
    const payload = buildSalePayload({
        total: total,
        paid_amount: paidValue,
        change_amount: changeValue,
        draft_id: currentDraftId,
    });

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
                const paidValue =
                    parseFloat(
                        document.getElementById("paidAmountInput").value,
                    ) || total;
                const changeValue = Math.max(0, paidValue - total);

                // Save transaction details for printing
                lastTransaction = {
                    orderNumber: data.order_number,
                    items: [...cart],
                    total: total,
                    paid: paidValue,
                    change: changeValue,
                    paymentMethod: selectedPaymentMethod,
                    orderType: selectedOrderType,
                    splitBillGroup: splitBillGroup,
                };

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

function goToStep(stepNumber) {
    if (stepNumber === 1) {
        document.getElementById("payment-modal-1").classList.remove("hidden");
        document.getElementById("payment-modal-2").classList.add("hidden");
    } else if (stepNumber === 2) {
        document.getElementById("payment-modal-1").classList.add("hidden");
        document.getElementById("payment-modal-2").classList.remove("hidden");
    }
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
    if (!activeRegisterSession || !activeRegisterSession.id) {
        alert("Pilih kasir dan open register terlebih dahulu.");
        showRegisterPicker();
        return;
    }

    const holdBtn = document.getElementById("holdBtn");
    holdBtn.disabled = true;

    const { total } = calculateTotals();
    const payload = buildSalePayload({
        total: total,
        draft_id: currentDraftId,
    });

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
            if (data.success) {
                showToast(`Order ${data.order_number} berhasil di-hold!`);
                if (!currentDraftId) {
                    currentDraftId = data.sale_id;
                }
                clearCart();
                loadDraftCount();
            } else {
                showToast(data.message || "Gagal hold order");
            }
        })
        .catch(() => {
            holdBtn.disabled = false;
            showToast("Gagal hold order. Coba lagi.");
        });
}

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
                // Ensure all prices are numbers
                const items = Array.isArray(data.items)
                    ? data.items.map((item) => ({
                          ...item,
                          price: parseFloat(item.price) || 0,
                          qty: parseInt(item.qty) || 1,
                      }))
                    : [];
                cart = hydrateCartItems(items);
                currentDraftId = data.draft_id;
                selectedOrderType = data.order_type || "dine_in";
                selectedPaymentMethod = data.payment_method || "cash";
                selectedTableId = data.table_id || null;
                splitBillGroup = data.split_bill_group || "";
                saveCart();
                renderCart();
                selectOrderType(selectedOrderType);
                selectPaymentMethod(selectedPaymentMethod);
                const tableSelect = document.getElementById("tableSelect");
                if (tableSelect) tableSelect.value = selectedTableId || "";
                const splitBillInput =
                    document.getElementById("splitBillInput");
                if (splitBillInput) splitBillInput.value = splitBillGroup || "";
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
        routeRegisterStatus = posConfig.dataset.routeRegisterStatus || "";
        csrfToken = posConfig.dataset.csrfToken || "";
        try {
            modifiersList = JSON.parse(posConfig.dataset.modifiers || "[]");
            productModifierMap = JSON.parse(
                posConfig.dataset.productModifiers || "{}",
            );
            tablesList = JSON.parse(posConfig.dataset.tables || "[]");
            registersList = JSON.parse(posConfig.dataset.registers || "[]");
            const activeSessionRaw = posConfig.dataset.activeRegisterSession || "null";
            const activeSessionParsed = JSON.parse(activeSessionRaw);
            activeRegisterSession = activeSessionParsed
                ? {
                      id: activeSessionParsed.id,
                      register_id: activeSessionParsed.register_id,
                      register_name: activeSessionParsed.register?.name || "",
                  }
                : null;
        } catch (error) {
            modifiersList = [];
            productModifierMap = {};
            tablesList = [];
            registersList = [];
            activeRegisterSession = null;
        }
    }

    cart = hydrateCartItems(loadCartWithPriority());

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
    selectOrderType(selectedOrderType);
    selectPaymentMethod(selectedPaymentMethod);
    const tableSelect = document.getElementById("tableSelect");
    if (tableSelect) tableSelect.value = selectedTableId || "";
    const splitBillInput = document.getElementById("splitBillInput");
    if (splitBillInput) splitBillInput.value = splitBillGroup || "";
    updateFloatingBadge();
    updateRegisterInfoLabel();
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
