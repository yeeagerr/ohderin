<div id="pageLoadingOverlay" class="loading-overlay" style="display: none;">
    <div class="loading-overlay-backdrop"></div>
    <div class="loading-overlay-content">
        <div class="loading-icon-wrapper">
            <div class="loading-plate">
                <svg class="loading-plate-svg" viewBox="0 0 80 80" fill="none">
                    <ellipse cx="40" cy="60" rx="30" ry="8" fill="#e0e0e0" opacity="0.5"/>
                    <ellipse cx="40" cy="55" rx="28" ry="6" fill="#f5f5f5"/>
                    <circle cx="40" cy="38" r="18" fill="#FF6B35" opacity="0.15"/>
                    <path d="M32 35 Q36 25 40 35 Q44 25 48 35" stroke="#FF6B35" stroke-width="2.5" fill="none" stroke-linecap="round" class="loading-steam"/>
                    <path d="M35 32 Q38 24 41 32" stroke="#FF6B35" stroke-width="2" fill="none" stroke-linecap="round" class="loading-steam steam-2"/>
                    <path d="M39 33 Q42 23 45 33" stroke="#FF6B35" stroke-width="2" fill="none" stroke-linecap="round" class="loading-steam steam-3"/>
                </svg>
            </div>
            <div class="loading-bounce-dots">
                <span class="bounce-dot"></span>
                <span class="bounce-dot"></span>
                <span class="bounce-dot"></span>
            </div>
        </div>
        <p class="loading-text" id="loadingText">Loading...</p>
        <p class="loading-subtext" id="loadingSubtext">Siap-siap ya!</p>
    </div>
</div>

<style>
    .loading-overlay {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .loading-overlay.active {
        opacity: 1;
    }
    .loading-overlay-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15, 15, 30, 0.75);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
    .loading-overlay-content {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        animation: loadingContentPop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        transform: scale(0.8);
    }
    @keyframes loadingContentPop {
        to { transform: scale(1); }
    }

    /* Icon wrapper */
    .loading-icon-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
    }
    .loading-plate {
        width: 100px;
        height: 100px;
        animation: loadingPlateFloat 2s ease-in-out infinite;
    }
    .loading-plate-svg {
        width: 100%;
        height: 100%;
    }
    @keyframes loadingPlateFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    /* Steam animation */
    .loading-steam {
        animation: steamRise 1.5s ease-in-out infinite;
        opacity: 0.7;
    }
    .loading-steam.steam-2 {
        animation-delay: 0.3s;
    }
    .loading-steam.steam-3 {
        animation-delay: 0.6s;
    }
    @keyframes steamRise {
        0% { transform: translateY(0); opacity: 0.7; }
        50% { transform: translateY(-6px); opacity: 0.3; }
        100% { transform: translateY(0); opacity: 0.7; }
    }

    /* Bouncing dots */
    .loading-bounce-dots {
        display: flex;
        gap: 8px;
    }
    .bounce-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FF6B35, #FF8F65);
        animation: bounceDot 1.4s ease-in-out infinite;
        box-shadow: 0 2px 8px rgba(255, 107, 53, 0.4);
    }
    .bounce-dot:nth-child(2) { animation-delay: 0.2s; }
    .bounce-dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes bounceDot {
        0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
        40% { transform: scale(1.2); opacity: 1; }
    }

    /* Text styles */
    .loading-text {
        font-family: 'Nunito', 'Inter', sans-serif;
        font-size: 1.35rem;
        font-weight: 800;
        color: #ffffff;
        letter-spacing: 0.02em;
        text-align: center;
        animation: loadingTextPulse 2s ease-in-out infinite;
    }
    .loading-subtext {
        font-family: 'Nunito', 'Inter', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.55);
        text-align: center;
        margin-top: -12px;
    }
    @keyframes loadingTextPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
</style>

<script>
(function() {
    const funMessages = [
        { text: "Tunggu ya.. 🍳", sub: "Lagi masak dulu nih!" },
        { text: "Bentar ya.. ⏳", sub: "Sabar dikit lagi~" },
        { text: "Loading.. 🚀", sub: "Terbang ke halaman selanjutnya!" },
        { text: "Otw yaa.. 🏃", sub: "Lari secepat kilat!" },
        { text: "Siap-siap.. 🎬", sub: "3.. 2.. 1.. Action!" },
        { text: "Sabar boss.. 😎", sub: "Yang sabar itu disayang~" },
        { text: "Eitss.. tunggu! ✋", sub: "Sedang mempersiapkan semuanya!" },
        { text: "Jangan ke mana-mana! 🍿", sub: "Sebentar lagi selesai~" },
        { text: "Mau ke mana nih? 🗺️", sub: "Aku antar ya!" },
        { text: "Nyiapin meja.. 🍽️", sub: "Biar rapi dan siap!" },
        { text: "Cuss gasskeun! 🔥", sub: "Langsung gas pol!" },
        { text: "Wush wush.. 💨", sub: "Kecepatan maksimal!" },
        { text: "Bentar doang kok.. ☕", sub: "Sambil ngopi dulu~" },
        { text: "Hampir sampai.. 📍", sub: "Tinggal selangkah lagi!" },
        { text: "Proses ya.. ⚡", sub: "Kilat kok tenang aja!" },
        { text: "Lagi nge-load.. 📦", sub: "Unboxing halaman baru!" },
        { text: "Harap tunggu.. 🎯", sub: "Sedang membidik target!" },
    ];

    function getRandomMessage() {
        return funMessages[Math.floor(Math.random() * funMessages.length)];
    }

    function showLoadingOverlay() {
        const overlay = document.getElementById('pageLoadingOverlay');
        const textEl = document.getElementById('loadingText');
        const subEl = document.getElementById('loadingSubtext');
        if (!overlay) return;

        const msg = getRandomMessage();
        textEl.textContent = msg.text;
        subEl.textContent = msg.sub;

        overlay.style.display = 'flex';
        void overlay.offsetWidth;
        overlay.classList.add('active');

        const interval = setInterval(() => {
            const newMsg = getRandomMessage();
            textEl.style.opacity = '0';
            textEl.style.transition = 'opacity 0.3s ease';
            subEl.style.opacity = '0';
            subEl.style.transition = 'opacity 0.3s ease';
            setTimeout(() => {
                textEl.textContent = newMsg.text;
                subEl.textContent = newMsg.sub;
                textEl.style.opacity = '1';
                subEl.style.opacity = '1';
            }, 300);
        }, 2500);

        overlay._interval = interval;
    }

    function hideLoadingOverlay() {
        const overlay = document.getElementById('pageLoadingOverlay');
        if (!overlay) return;
        if (overlay._interval) clearInterval(overlay._interval);
        overlay.classList.remove('active');
        setTimeout(() => { overlay.style.display = 'none'; }, 300);
    }

    // intersepsi klik link     
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a[href]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                // skip anchornya void js
                if (!href || href === '#' || href.startsWith('javascript:') || href.startsWith('#')) return;
                // Skip kalau buka di new tab
                if (this.target === '_blank') return;
                // Skip kalau ctrl/cmd/shift 
                if (e.ctrlKey || e.metaKey || e.shiftKey) return;

                e.preventDefault();
                showLoadingOverlay();
                setTimeout(function() {
                    window.location.href = href;
                }, 400);
            });
        });

        // Hide overlay when coming back via browser back button
        window.addEventListener('pageshow', function(e) {
            if (e.persisted) {
                hideLoadingOverlay();
            }
        });
    });

    // Expose globally
    window.showLoadingOverlay = showLoadingOverlay;
    window.hideLoadingOverlay = hideLoadingOverlay;
})();
</script>
