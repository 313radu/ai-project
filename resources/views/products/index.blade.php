<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affiliate Deals — AI Project</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
            min-height: 100vh;
        }

        /* ── HEADER ── */
        header {
            background: #01696f;
            color: white;
            padding: 0 2rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 12px rgba(0,0,0,0.15);
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.2rem;
            font-weight: 700;
            text-decoration: none;
            color: white;
        }
        .logo svg { flex-shrink: 0; }
        .header-right { font-size: 0.85rem; opacity: 0.85; }

        /* ── CONTAINER ── */
        .container { max-width: 1280px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* ── HERO STRIP ── */
        .hero {
            background: linear-gradient(135deg, #01696f 0%, #0c4e54 100%);
            color: white;
            border-radius: 16px;
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .hero h2 { font-size: 1.6rem; font-weight: 700; }
        .hero p  { font-size: 0.95rem; opacity: 0.85; margin-top: 4px; }
        .hero-badge {
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 50px;
            padding: 6px 18px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* ── FILTERS ── */
        .filters-bar {
            background: white;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            margin-bottom: 1.5rem;
        }
        .search-wrap {
            position: relative;
            flex: 1;
            min-width: 200px;
        }
        .search-wrap svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            pointer-events: none;
        }
        .search-wrap input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
            outline: none;
        }
        .search-wrap input:focus { border-color: #01696f; }
        .filters-bar select {
            padding: 10px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            background: white;
            cursor: pointer;
            outline: none;
            transition: border-color 0.2s;
        }
        .filters-bar select:focus { border-color: #01696f; }
        .btn-filter {
            padding: 10px 22px;
            background: #01696f;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background 0.2s;
        }
        .btn-filter:hover { background: #0c4e54; }
        .btn-reset {
            padding: 10px 18px;
            background: #f3f4f6;
            color: #555;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.9rem;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            transition: background 0.2s;
        }
        .btn-reset:hover { background: #e5e7eb; }

        /* ── COUNT ── */
        .results-count {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 1.2rem;
        }
        .results-count strong { color: #1a1a2e; }

        /* ── GRID ── */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.25rem;
        }

        /* ── CARD ── */
        .card {
            background: white;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
            transition: transform 0.22s ease, box-shadow 0.22s ease;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        /* discount badge */
        .badge-discount {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #e53e3e;
            color: white;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 50px;
            z-index: 2;
            letter-spacing: 0.3px;
        }

        /* image area */
        .card-img {
            width: 100%;
            height: 190px;
            object-fit: contain;
            background: #fafafa;
            padding: 12px;
        }
        .card-img-placeholder {
            width: 100%;
            height: 190px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
        }

        /* body */
        .card-body {
            padding: 1rem 1rem 1.1rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .card-category {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #01696f;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .card-name {
            font-size: 0.93rem;
            font-weight: 600;
            color: #1a1a2e;
            line-height: 1.45;
            margin-bottom: 8px;
            flex: 1;
        }
        .card-merchant {
            font-size: 0.78rem;
            color: #9ca3af;
            margin-bottom: 10px;
        }

        /* pricing row */
        .price-row {
            display: flex;
            align-items: baseline;
            gap: 7px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }
        .price-current {
            font-size: 1.25rem;
            font-weight: 700;
            color: #01696f;
        }
        .price-old {
            font-size: 0.82rem;
            color: #bbb;
            text-decoration: line-through;
        }
        .price-currency { font-size: 0.85rem; color: #6b7280; font-weight: 500; }

        /* CTA buttons */
        .card-actions { display: flex; gap: 8px; }
        .btn-detail {
            flex: 1;
            text-align: center;
            padding: 9px 10px;
            border: 1.5px solid #01696f;
            border-radius: 8px;
            color: #01696f;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: background 0.18s, color 0.18s;
        }
        .btn-detail:hover { background: #01696f; color: white; }
        .btn-buy {
            flex: 2;
            text-align: center;
            padding: 9px 10px;
            background: #01696f;
            border: 1.5px solid #01696f;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: background 0.18s;
        }
        .btn-buy:hover { background: #0c4e54; border-color: #0c4e54; }

        /* ── EMPTY STATE ── */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 5rem 2rem;
            color: #9ca3af;
        }
        .empty-state .empty-icon { font-size: 3.5rem; margin-bottom: 1rem; }
        .empty-state h3 { font-size: 1.2rem; color: #4b5563; margin-bottom: 0.5rem; }
        .empty-state code {
            background: #f3f4f6;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.85rem;
            color: #374151;
        }

        /* ── PAGINATION ── */
        .pager {
            margin-top: 2.5rem;
            display: flex;
            justify-content: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        .pager a, .pager span {
            padding: 7px 13px;
            border-radius: 7px;
            border: 1.5px solid #e5e7eb;
            text-decoration: none;
            color: #374151;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.18s;
        }
        .pager a:hover { background: #f3f4f6; border-color: #01696f; color: #01696f; }
        .pager .active span { background: #01696f; color: white; border-color: #01696f; }

        /* ── RESPONSIVE ── */
        @media (max-width: 640px) {
            .hero { padding: 1.5rem; }
            .hero h2 { font-size: 1.3rem; }
            .grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 0.9rem; }
            .card-img, .card-img-placeholder { height: 140px; }
            .price-current { font-size: 1.1rem; }
        }
    </style>
</head>
<body>

<header>
    <a href="/" class="logo">
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
            <rect width="28" height="28" rx="8" fill="rgba(255,255,255,0.2)"/>
            <path d="M7 14h14M14 7l7 7-7 7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        AI Deals
    </a>
    <span class="header-right">Powered by 2Performant</span>
</header>

<div class="container">

    {{-- Hero --}}
    <div class="hero">
        <div>
            <h2>🔥 Cele mai bune oferte affiliate</h2>
            <p>Produse selectate cu reduceri reale din magazinele partenere</p>
        </div>
        <span class="hero-badge">{{ $products->total() }} produse</span>
    </div>

    {{-- Filters --}}
    <form class="filters-bar" method="GET">
        <div class="search-wrap">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
            </svg>
            <input type="text" name="search" placeholder="Caută produs..." value="{{ request('search') }}">
        </div>
        <select name="category">
            <option value="">Toate categoriile</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-filter">Caută</button>
        @if(request()->hasAny(['search','category']))
            <a href="/products" class="btn-reset">✕ Reset</a>
        @endif
    </form>

    <p class="results-count"><strong>{{ $products->total() }}</strong> produse găsite</p>

    {{-- Grid --}}
    <div class="grid">
        @if($products->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">📦</div>
                <h3>Niciun produs găsit</h3>
                <p style="margin-top:8px;">Sincronizează foaia cu: <code>php artisan products:sync-sheet</code></p>
            </div>
        @else
            @foreach($products as $product)
                <div class="card">
                    @if($product->discount_percent)
                        <span class="badge-discount">-{{ $product->discount_percent }}%</span>
                    @endif

                    @if($product->image_url)
                        <img class="card-img" src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                    @else
                        <div class="card-img-placeholder">🛍️</div>
                    @endif

                    <div class="card-body">
                        @if($product->category)
                            <span class="card-category">{{ $product->category }}</span>
                        @endif
                        <p class="card-name">{{ Str::limit($product->name, 65) }}</p>
                        @if($product->merchant)
                            <p class="card-merchant">📍 {{ $product->merchant }}</p>
                        @endif

                        <div class="price-row">
                            @if($product->price)
                                <span class="price-current">{{ number_format($product->price, 2) }}</span>
                                <span class="price-currency">{{ $product->currency ?? 'RON' }}</span>
                            @endif
                            @if($product->old_price)
                                <span class="price-old">{{ number_format($product->old_price, 2) }}</span>
                            @endif
                        </div>

                        <div class="card-actions">
                            <a href="{{ route('products.show', $product->slug) }}" class="btn-detail">Detalii</a>
                            <a href="{{ route('products.redirect', $product->slug) }}" class="btn-buy" target="_blank" rel="noopener">Cumpără →</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="pager">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif

</div>
</body>
</html>
