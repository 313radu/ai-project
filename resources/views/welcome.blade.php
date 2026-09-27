<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Affiliate — Deals & Products</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300..700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:         #f7f6f2;
            --surface:    #ffffff;
            --surface-2:  #f3f0ec;
            --border:     rgba(0,0,0,0.08);
            --text:       #1a1916;
            --muted:      #6b6a66;
            --faint:      #b0aea9;
            --primary:    #01696f;
            --primary-h:  #0c4e54;
            --accent:     #da7101;
            --radius:     14px;
            --radius-sm:  8px;
            --shadow:     0 2px 8px rgba(0,0,0,0.06), 0 8px 24px rgba(0,0,0,0.04);
            --shadow-h:   0 4px 16px rgba(0,0,0,0.10), 0 16px 40px rgba(0,0,0,0.07);
            --font-body:  'Inter', sans-serif;
            --font-disp:  'Instrument Serif', serif;
        }
        [data-theme="dark"] {
            --bg:         #141312;
            --surface:    #1c1b19;
            --surface-2:  #222120;
            --border:     rgba(255,255,255,0.07);
            --text:       #d4d3d0;
            --muted:      #7a7976;
            --faint:      #4a4947;
            --primary:    #4f98a3;
            --primary-h:  #62adb9;
            --accent:     #fdab43;
        }

        html { -webkit-font-smoothing: antialiased; scroll-behavior: smooth; }
        body { font-family: var(--font-body); background: var(--bg); color: var(--text); min-height: 100dvh; font-size: 16px; line-height: 1.6; transition: background .3s, color .3s; }

        /* ── Header ── */
        .header {
            position: sticky; top: 0; z-index: 100;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
            padding: 0 24px;
            height: 60px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .logo {
            display: flex; align-items: center; gap: 10px;
            font-family: var(--font-disp); font-size: 1.25rem; color: var(--text);
            text-decoration: none;
        }
        .logo svg { color: var(--primary); }
        .header-right { display: flex; align-items: center; gap: 12px; }
        .nav-link { color: var(--muted); text-decoration: none; font-size: 0.875rem; font-weight: 500; padding: 6px 10px; border-radius: 6px; transition: color .2s, background .2s; }
        .nav-link:hover { color: var(--text); background: var(--surface-2); }
        .btn-theme {
            width: 36px; height: 36px; border: 1px solid var(--border); border-radius: 8px;
            background: transparent; color: var(--muted); cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: background .2s, color .2s;
        }
        .btn-theme:hover { background: var(--surface-2); color: var(--text); }

        /* ── Hero ── */
        .hero {
            max-width: 860px; margin: 0 auto; padding: 64px 24px 40px;
            text-align: center;
        }
        .hero-badge {
            display: inline-block; background: color-mix(in oklab, var(--primary) 12%, transparent);
            color: var(--primary); border: 1px solid color-mix(in oklab, var(--primary) 25%, transparent);
            font-size: 0.75rem; font-weight: 600; letter-spacing: .06em; text-transform: uppercase;
            padding: 4px 14px; border-radius: 999px; margin-bottom: 20px;
        }
        .hero h1 { font-family: var(--font-disp); font-size: clamp(2rem, 5vw, 3.5rem); line-height: 1.15; color: var(--text); margin-bottom: 16px; }
        .hero h1 em { font-style: italic; color: var(--primary); }
        .hero p { color: var(--muted); font-size: 1.05rem; max-width: 520px; margin: 0 auto 32px; }

        /* ── Search ── */
        .search-wrap { max-width: 600px; margin: 0 auto 56px; }
        .search-box {
            display: flex; gap: 0;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: border-color .2s, box-shadow .2s;
        }
        .search-box:focus-within { border-color: var(--primary); box-shadow: 0 0 0 3px color-mix(in oklab, var(--primary) 18%, transparent); }
        .search-box input {
            flex: 1; padding: 14px 18px; border: none; background: transparent;
            font-size: 0.95rem; color: var(--text); outline: none;
            font-family: var(--font-body);
        }
        .search-box input::placeholder { color: var(--faint); }
        .btn-search {
            padding: 12px 22px; background: var(--primary); color: white;
            border: none; font-size: 0.9rem; font-weight: 600; cursor: pointer;
            display: flex; align-items: center; gap: 8px;
            transition: background .2s;
        }
        .btn-search:hover { background: var(--primary-h); }
        #ai-response {
            margin-top: 14px; padding: 14px 18px;
            background: var(--surface-2); border: 1px solid var(--border);
            border-radius: var(--radius-sm); font-size: 0.9rem; color: var(--muted);
            text-align: left; display: none; white-space: pre-wrap; line-height: 1.7;
        }
        #ai-response.visible { display: block; }

        /* ── Section title ── */
        .section-head {
            max-width: 1200px; margin: 0 auto; padding: 0 24px 20px;
            display: flex; align-items: baseline; justify-content: space-between;
        }
        .section-head h2 { font-size: 1.25rem; font-weight: 700; }
        .section-head a { font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 500; }
        .section-head a:hover { text-decoration: underline; }

        /* ── Product Grid ── */
        .products-grid {
            max-width: 1200px; margin: 0 auto; padding: 0 24px 80px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(260px, 100%), 1fr));
            gap: 20px;
        }

        /* ── Product Card ── */
        .product-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            display: flex; flex-direction: column;
            transition: transform .22s cubic-bezier(.16,1,.3,1), box-shadow .22s cubic-bezier(.16,1,.3,1);
        }
        .product-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-h); }

        .card-img-wrap {
            position: relative; background: var(--surface-2);
            aspect-ratio: 4/3; overflow: hidden;
        }
        .card-img-wrap img {
            width: 100%; height: 100%; object-fit: cover; display: block;
            transition: transform .4s ease;
        }
        .product-card:hover .card-img-wrap img { transform: scale(1.04); }

        .card-img-placeholder {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            color: var(--faint);
        }

        .badge-discount {
            position: absolute; top: 10px; left: 10px;
            background: var(--accent); color: white;
            font-size: 0.72rem; font-weight: 700; letter-spacing: .04em;
            padding: 3px 10px; border-radius: 999px;
        }
        .badge-new {
            position: absolute; top: 10px; right: 10px;
            background: var(--primary); color: white;
            font-size: 0.72rem; font-weight: 700; letter-spacing: .04em;
            padding: 3px 10px; border-radius: 999px;
        }

        .card-body { padding: 16px; flex: 1; display: flex; flex-direction: column; }
        .card-merchant { font-size: 0.72rem; text-transform: uppercase; letter-spacing: .07em; color: var(--faint); font-weight: 600; margin-bottom: 6px; }
        .card-name {
            font-size: 0.95rem; font-weight: 600; color: var(--text);
            line-height: 1.4; margin-bottom: 8px;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        .card-desc {
            font-size: 0.82rem; color: var(--muted); line-height: 1.55;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
            margin-bottom: 14px; flex: 1;
        }
        .card-footer { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-top: auto; }
        .card-price { font-size: 1.1rem; font-weight: 700; color: var(--text); }
        .card-price-old { font-size: 0.8rem; color: var(--faint); text-decoration: line-through; margin-left: 4px; font-weight: 400; }
        .btn-buy {
            padding: 8px 16px; background: var(--primary); color: white;
            border-radius: var(--radius-sm); font-size: 0.82rem; font-weight: 600;
            text-decoration: none; transition: background .18s; white-space: nowrap;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-buy:hover { background: var(--primary-h); }

        /* ── Empty state ── */
        .empty-state {
            grid-column: 1 / -1; padding: 60px 20px;
            display: flex; flex-direction: column; align-items: center; text-align: center; gap: 12px;
        }
        .empty-state svg { color: var(--faint); }
        .empty-state h3 { font-size: 1.1rem; color: var(--text); }
        .empty-state p { font-size: 0.9rem; color: var(--muted); max-width: 32ch; }
        .empty-state a { color: var(--primary); font-weight: 600; text-decoration: none; font-size: 0.9rem; }

        /* ── Footer ── */
        footer { border-top: 1px solid var(--border); padding: 24px; text-align: center; font-size: 0.82rem; color: var(--faint); }

        @media (max-width: 600px) {
            .hero { padding: 40px 16px 24px; }
            .hero h1 { font-size: 1.8rem; }
            .products-grid { padding: 0 16px 60px; gap: 14px; }
            .section-head { padding: 0 16px 16px; }
        }
    </style>
</head>
<body>

<!-- ── Header ── -->
<header class="header">
    <a href="/" class="logo">
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" aria-label="Logo">
            <rect width="28" height="28" rx="7" fill="currentColor" opacity=".12"/>
            <path d="M7 14 L14 7 L21 14 L14 21 Z" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
            <circle cx="14" cy="14" r="3" fill="var(--bg)"/>
        </svg>
        AI Affiliate
    </a>
    <div class="header-right">
        <a href="/products" class="nav-link">All Products</a>
        <button class="btn-theme" data-theme-toggle aria-label="Toggle dark mode">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </button>
    </div>
</header>

<!-- ── Hero ── -->
<section class="hero">
    <span class="hero-badge">🔥 Best Deals Live</span>
    <h1>Find the best products<br><em>at the lowest price</em></h1>
    <p>AI-powered affiliate search. Ask anything and discover the top deals from top merchants.</p>

    <div class="search-wrap">
        <div class="search-box">
            <input type="text" id="search-input" placeholder="e.g. gaming laptop under 3000 RON…" autocomplete="off">
            <button class="btn-search" id="ask-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                Ask AI
            </button>
        </div>
        <div id="ai-response"></div>
    </div>
</section>

<!-- ── Products ── -->
<div class="section-head">
    <h2>Featured Products</h2>
    <a href="/products">View all →</a>
</div>

<div class="products-grid">
    @forelse($products as $product)
    <article class="product-card">
        <div class="card-img-wrap">
            @if($product->image_url)
                <img
                    src="{{ $product->image_url }}"
                    alt="{{ $product->name }}"
                    loading="lazy"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                >
                <div class="card-img-placeholder" style="display:none">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                </div>
            @else
                <div class="card-img-placeholder">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                </div>
            @endif

            @if($product->discount_percent > 0)
                <span class="badge-discount">-{{ $product->discount_percent }}%</span>
            @endif
        </div>

        <div class="card-body">
            @if($product->merchant)
                <div class="card-merchant">{{ $product->merchant }}</div>
            @endif
            <div class="card-name">{{ $product->name }}</div>
            @if($product->description)
                <div class="card-desc">{{ $product->description }}</div>
            @endif
            <div class="card-footer">
                <div>
                    <span class="card-price">
                        {{ number_format($product->price, 2) }} RON
                    </span>
                    @if($product->original_price && $product->original_price > $product->price)
                        <span class="card-price-old">{{ number_format($product->original_price, 2) }}</span>
                    @endif
                </div>
                <a href="{{ route('products.go', $product->slug) }}" class="btn-buy" target="_blank" rel="noopener">
                    Buy
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </article>
    @empty
    <div class="empty-state">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        <h3>No products yet</h3>
        <p>Import your Google Sheet or add products to see them here.</p>
        <a href="/products/sync">Sync products →</a>
    </div>
    @endforelse
</div>

<footer>
    © {{ date('Y') }} AI Affiliate Platform — Built with Laravel
</footer>

<script>
    // Dark mode toggle
    (function(){
        const t = document.querySelector('[data-theme-toggle]');
        const r = document.documentElement;
        let d = matchMedia('(prefers-color-scheme:dark)').matches ? 'dark' : 'light';
        r.setAttribute('data-theme', d);
        const sunIcon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>';
        const moonIcon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';
        if (t) {
            t.innerHTML = d === 'dark' ? sunIcon : moonIcon;
            t.addEventListener('click', () => {
                d = d === 'dark' ? 'light' : 'dark';
                r.setAttribute('data-theme', d);
                t.innerHTML = d === 'dark' ? sunIcon : moonIcon;
            });
        }
    })();

    // AI Search
    const askBtn = document.getElementById('ask-btn');
    const searchInput = document.getElementById('search-input');
    const aiResponse = document.getElementById('ai-response');
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    askBtn.addEventListener('click', async () => {
        const message = searchInput.value.trim();
        if (!message) return;
        aiResponse.textContent = '⏳ Thinking…';
        aiResponse.classList.add('visible');
        try {
            const res = await fetch('/ai-chat', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: JSON.stringify({ message })
            });
            const data = await res.json();
            aiResponse.textContent = data.reply ?? 'No reply received.';
        } catch {
            aiResponse.textContent = '⚠️ Request failed. Check Laravel and Ollama.';
        }
    });

    searchInput.addEventListener('keydown', e => { if (e.key === 'Enter') askBtn.click(); });
</script>

</body>
</html>
