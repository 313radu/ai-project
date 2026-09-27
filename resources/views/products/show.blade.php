<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} — AI Deals</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #1a1a2e; min-height: 100vh; }

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
            display: flex; align-items: center; gap: 10px;
            font-size: 1.2rem; font-weight: 700;
            text-decoration: none; color: white;
        }
        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            color: #01696f; text-decoration: none; font-size: 0.9rem;
            font-weight: 500; margin-bottom: 1.5rem;
            transition: gap 0.2s;
        }
        .back-link:hover { gap: 10px; }

        .container { max-width: 1000px; margin: 0 auto; padding: 2rem 1.5rem; }

        .product-wrap {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 0;
        }

        .product-gallery {
            background: #fafafa;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            border-right: 1px solid #f3f4f6;
            min-height: 360px;
        }
        .product-gallery img {
            max-width: 100%;
            max-height: 320px;
            object-fit: contain;
        }
        .product-gallery .no-img {
            font-size: 5rem;
            opacity: 0.3;
        }

        .product-info { padding: 2rem 2.5rem; }

        .info-category {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #01696f;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .info-name {
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.35;
            margin-bottom: 8px;
        }
        .info-brand {
            font-size: 0.85rem;
            color: #9ca3af;
            margin-bottom: 1.5rem;
        }
        .info-brand span { color: #4b5563; font-weight: 500; }

        .price-block {
            background: #f0faf9;
            border: 1.5px solid #b2dfdb;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }
        .price-main { font-size: 2rem; font-weight: 700; color: #01696f; }
        .price-currency { font-size: 1rem; color: #6b7280; }
        .price-old { font-size: 0.9rem; color: #bbb; text-decoration: line-through; }
        .badge-discount {
            background: #e53e3e;
            color: white;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 50px;
        }

        .info-desc {
            font-size: 0.95rem;
            line-height: 1.7;
            color: #4b5563;
            margin-bottom: 1.5rem;
        }

        .info-meta {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 1.75rem;
            font-size: 0.85rem;
            color: #6b7280;
        }
        .info-meta span { display: flex; align-items: center; gap: 6px; }
        .info-meta strong { color: #374151; }

        .actions { display: flex; gap: 12px; flex-wrap: wrap; }
        .btn-buy {
            flex: 1;
            min-width: 180px;
            text-align: center;
            padding: 14px 24px;
            background: #01696f;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 700;
            transition: background 0.2s, transform 0.15s;
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-buy:hover { background: #0c4e54; transform: translateY(-1px); }
        .btn-back-alt {
            padding: 14px 20px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            color: #374151;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: border-color 0.2s, background 0.2s;
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        }
        .btn-back-alt:hover { border-color: #01696f; color: #01696f; background: #f0faf9; }

        @media (max-width: 720px) {
            .product-wrap { grid-template-columns: 1fr; }
            .product-gallery { border-right: none; border-bottom: 1px solid #f3f4f6; min-height: 240px; }
            .product-info { padding: 1.5rem; }
            .info-name { font-size: 1.2rem; }
            .price-main { font-size: 1.6rem; }
        }
    </style>
</head>
<body>

<header>
    <a href="/products" class="logo">
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
            <rect width="28" height="28" rx="8" fill="rgba(255,255,255,0.2)"/>
            <path d="M7 14h14M14 7l7 7-7 7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        AI Deals
    </a>
    <span style="font-size:0.85rem;opacity:0.85;">Powered by 2Performant</span>
</header>

<div class="container">
    <a href="/products" class="back-link">
        ← Înapoi la produse
    </a>

    <div class="product-wrap">
        {{-- Gallery --}}
        <div class="product-gallery">
            @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            @else
                <div class="no-img">🛍️</div>
            @endif
        </div>

        {{-- Info --}}
        <div class="product-info">
            @if($product->category)
                <p class="info-category">{{ $product->category }}</p>
            @endif

            <h1 class="info-name">{{ $product->name }}</h1>

            @if($product->brand || $product->merchant)
                <p class="info-brand">
                    @if($product->brand) Brand: <span>{{ $product->brand }}</span> &nbsp;·&nbsp; @endif
                    @if($product->merchant) Magazin: <span>{{ $product->merchant }}</span> @endif
                </p>
            @endif

            {{-- Price block --}}
            <div class="price-block">
                @if($product->price)
                    <span class="price-main">{{ number_format($product->price, 2) }}</span>
                    <span class="price-currency">{{ $product->currency ?? 'RON' }}</span>
                @endif
                @if($product->old_price)
                    <span class="price-old">{{ number_format($product->old_price, 2) }} {{ $product->currency ?? 'RON' }}</span>
                @endif
                @if($product->discount_percent)
                    <span class="badge-discount">-{{ $product->discount_percent }}% REDUCERE</span>
                @endif
            </div>

            {{-- Description --}}
            @if($product->description)
                <p class="info-desc">{{ $product->description }}</p>
            @endif

            {{-- Meta --}}
            <div class="info-meta">
                @if($product->network)
                    <span>🔗 Rețea: <strong>{{ $product->network }}</strong></span>
                @endif
                @if($product->source)
                    <span>📋 Sursă: <strong>{{ ucfirst($product->source) }}</strong></span>
                @endif
                @if($product->last_synced_at)
                    <span>🔄 Sincronizat: <strong>{{ $product->last_synced_at->diffForHumans() }}</strong></span>
                @endif
            </div>

            {{-- CTA --}}
            <div class="actions">
                <a href="{{ route('products.redirect', $product->slug) }}" class="btn-buy" target="_blank" rel="noopener">
                    🛒 Cumpără acum
                </a>
                <a href="/products" class="btn-back-alt">← Toate produsele</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
