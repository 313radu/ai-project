<!DOCTYPE html>
<html lang="ro" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} — AI Deals</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="site-shell">
    @include('partials.site-header')

    <main class="site-main">
        <div class="site-container detail-layout">
            <a href="{{ route('products.index') }}" class="back-link"><span aria-hidden="true">←</span> Înapoi la produse</a>

            <article class="product-wrap">
                <div class="product-gallery">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    @else
                        <div class="no-img" aria-hidden="true">🛍️</div>
                    @endif
                </div>

                <div class="product-info">
                    @if($product->category)
                        <p class="info-category">{{ $product->category }}</p>
                    @endif

                    <h1 class="info-name">{{ $product->name }}</h1>

                    @if($product->brand || $product->merchant)
                        <p class="info-brand">
                            @if($product->brand) Brand: <span>{{ $product->brand }}</span> @endif
                            @if($product->brand && $product->merchant) <span aria-hidden="true"> · </span> @endif
                            @if($product->merchant) Magazin: <span>{{ $product->merchant }}</span> @endif
                        </p>
                    @endif

                    <div class="price-block">
                        @if($product->price)
                            <span class="price-main">{{ number_format($product->price, 2) }}</span>
                            <span class="price-currency">{{ $product->currency ?? 'RON' }}</span>
                        @endif
                        @if($product->old_price)
                            <span class="price-old">{{ number_format($product->old_price, 2) }} {{ $product->currency ?? 'RON' }}</span>
                        @endif
                        @if($product->discount_percent)
                            <span class="badge-discount">-{{ $product->discount_percent }}% reducere</span>
                        @endif
                    </div>

                    @if($product->description)
                        <p class="info-desc">{{ $product->description }}</p>
                    @endif

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

                    <div class="actions">
                        <a href="{{ route('products.go', $product->slug) }}" class="btn-buy" target="_blank" rel="noopener">
                            🛒 Cumpără acum <span aria-hidden="true">↗</span>
                        </a>
                        <a href="{{ route('products.index') }}" class="btn-back-alt">← Toate produsele</a>
                    </div>
                </div>
            </article>
        </div>
    </main>

    @include('partials.site-footer')
</body>
</html>
