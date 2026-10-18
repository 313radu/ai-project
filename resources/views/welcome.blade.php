<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Deals — Smarter shopping, better prices</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
</head>
<body class="site-shell">
    @include('partials.site-header')

    <main class="site-main">
        <section class="home-hero">
            <span class="eyebrow">AI-powered shopping desk</span>
            <h1>Find the right product.<span>Keep more of your budget.</span></h1>
            <p class="home-hero__lead">Tell us what you need in plain language. Our shopping assistant searches the live catalog and brings the clearest deals to the surface.</p>

            <form class="ai-search" id="ai-form" data-ai-form>
                <label class="ai-search__label" for="search-input">What are you looking for?</label>
                <div class="ai-search__box">
                    <input type="text" id="search-input" placeholder="e.g. a gaming laptop under 3000 RON" autocomplete="off">
                    <button class="button-primary" type="submit" id="ask-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8" /><path d="m21 21-4.35-4.35" /></svg>
                        <span>Ask AI</span>
                    </button>
                </div>
                <div class="ai-response" id="ai-response" data-ai-response aria-live="polite"></div>
            </form>

            <div class="home-hero__proof" aria-label="Shopping assistant benefits">
                <span>Live product catalog</span>
                <span>Romanian prices</span>
                <span>Focused recommendations</span>
            </div>
        </section>

        <section class="site-container section-block" aria-labelledby="featured-products-title">
            <div class="section-heading">
                <div>
                    <h2 id="featured-products-title">Featured products</h2>
                    <p>A few smart picks from the latest catalog sync.</p>
                </div>
                <a class="text-link" href="{{ route('products.index') }}">Browse all products <span aria-hidden="true">→</span></a>
            </div>

            <div class="product-grid">
                @forelse($products as $product)
                    <article class="product-card">
                        <div class="product-card__media">
                            @if($product->image_url)
                                <img class="product-card__image" src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                                <div class="card-img-placeholder" hidden aria-hidden="true">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="3" /><circle cx="8.5" cy="8.5" r="1.5" /><path d="m21 15-5-5L5 21" /></svg>
                                </div>
                            @else
                                <div class="card-img-placeholder" aria-hidden="true">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="3" /><circle cx="8.5" cy="8.5" r="1.5" /><path d="m21 15-5-5L5 21" /></svg>
                                </div>
                            @endif

                            @if($product->discount_percent > 0)
                                <span class="badge-discount">-{{ $product->discount_percent }}%</span>
                            @endif
                        </div>

                        <div class="product-card__body">
                            @if($product->merchant)
                                <div class="card-merchant">{{ $product->merchant }}</div>
                            @endif
                            <h3 class="product-card__name">{{ $product->name }}</h3>
                            @if($product->description)
                                <p class="product-card__description">{{ $product->description }}</p>
                            @endif
                            <div class="product-card__footer">
                                <div>
                                    <span class="card-price">{{ number_format($product->price, 2) }} {{ $product->currency ?? 'RON' }}</span>
                                    @if($product->old_price && $product->old_price > $product->price)
                                        <span class="card-price-old">{{ number_format($product->old_price, 2) }} {{ $product->currency ?? 'RON' }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('products.go', $product->slug) }}" class="btn-buy" target="_blank" rel="noopener">
                                    Buy
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7" /></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" /><line x1="3" y1="6" x2="21" y2="6" /><path d="M16 10a4 4 0 0 1-8 0" /></svg>
                        <h3>No products yet</h3>
                        <p>Import your catalog to start discovering deals.</p>
                        <a href="{{ route('products.index') }}">View products <span aria-hidden="true">→</span></a>
                    </div>
                @endforelse
            </div>
        </section>
    </main>

    @include('partials.site-footer')
</body>
</html>
