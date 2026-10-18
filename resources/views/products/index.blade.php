<!DOCTYPE html>
<html lang="ro" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All products — AI Deals</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="site-shell">
    @include('partials.site-header')

    <main class="site-main">
        <div class="site-container catalog-layout">
            <section class="catalog-hero" aria-labelledby="catalog-title">
                <div>
                    <h1 id="catalog-title">Cele mai bune oferte, într-un singur loc.</h1>
                    <p>Produse selectate din magazinele partenere și organizate pentru o alegere mai ușoară.</p>
                </div>
                <span class="catalog-hero__badge">{{ $products->total() }} produse</span>
            </section>

            <form class="filter-bar" method="GET">
                <div class="field-with-icon">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8" /><path d="m21 21-4.35-4.35" /></svg>
                    <input type="text" name="search" placeholder="Caută un produs..." value="{{ request('search') }}" aria-label="Caută un produs">
                </div>
                <select name="category" aria-label="Alege categoria">
                    <option value="">Toate categoriile</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <button type="submit" class="button-filter">Caută</button>
                @if(request()->hasAny(['search', 'category']))
                    <a href="{{ route('products.index') }}" class="button-secondary">Reset</a>
                @endif
            </form>

            <p class="results-count"><strong>{{ $products->total() }}</strong> produse găsite</p>

            <div class="product-grid">
                @if($products->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon" aria-hidden="true">📦</div>
                        <h3>Niciun produs găsit</h3>
                        <p>Încearcă un alt termen sau sincronizează catalogul.</p>
                    </div>
                @else
                    @foreach($products as $product)
                        <article class="product-card">
                            @if($product->discount_percent)
                                <span class="badge-discount">-{{ $product->discount_percent }}%</span>
                            @endif

                            <div class="product-card__media">
                                @if($product->image_url)
                                    <img class="card-img" src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                                    <div class="card-img-placeholder" hidden aria-hidden="true">🛍️</div>
                                @else
                                    <div class="card-img-placeholder" aria-hidden="true">🛍️</div>
                                @endif
                            </div>

                            <div class="card-body">
                                @if($product->category)
                                    <span class="card-category">{{ $product->category }}</span>
                                @endif
                                <h2 class="card-name">{{ Str::limit($product->name, 65) }}</h2>
                                @if($product->merchant)
                                    <p class="card-merchant">{{ $product->merchant }}</p>
                                @endif

                                <div class="price-row">
                                    @if($product->price)
                                        <span class="price-current">{{ number_format($product->price, 2) }}</span>
                                        <span class="price-currency">{{ $product->currency ?? 'RON' }}</span>
                                    @endif
                                    @if($product->old_price)
                                        <span class="price-old">{{ number_format($product->old_price, 2) }} {{ $product->currency ?? 'RON' }}</span>
                                    @endif
                                </div>

                                <div class="card-actions">
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn-detail">Detalii</a>
                                    <a href="{{ route('products.go', $product->slug) }}" class="btn-buy" target="_blank" rel="noopener">Cumpără <span aria-hidden="true">→</span></a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @endif
            </div>

            @if($products->hasPages())
                <div class="pager">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </main>

    @include('partials.site-footer')
</body>
</html>
