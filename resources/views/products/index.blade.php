<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produse Affiliate</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f5f5f5; color: #222; }
        header { background: #01696f; color: white; padding: 1rem 2rem; }
        header h1 { font-size: 1.5rem; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .filters { display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .filters input, .filters select {
            padding: .5rem 1rem; border: 1px solid #ddd;
            border-radius: 6px; font-size: 1rem;
        }
        .filters button {
            padding: .5rem 1.2rem; background: #01696f; color: white;
            border: none; border-radius: 6px; cursor: pointer;
        }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.2rem; }
        .card {
            background: white; border-radius: 10px; overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,.07); transition: transform .2s;
        }
        .card:hover { transform: translateY(-3px); }
        .card img { width: 100%; height: 180px; object-fit: contain; background: #fafafa; padding: .5rem; }
        .card-body { padding: 1rem; }
        .card-body h3 { font-size: .95rem; margin-bottom: .4rem; }
        .price { font-size: 1.2rem; font-weight: bold; color: #01696f; }
        .old-price { font-size: .85rem; color: #999; text-decoration: line-through; margin-left: .4rem; }
        .discount { background: #e74c3c; color: white; font-size: .75rem; padding: .2rem .5rem; border-radius: 4px; margin-left: .5rem; }
        .merchant { font-size: .8rem; color: #777; margin-top: .3rem; }
        .btn { display: block; margin-top: .8rem; text-align: center; background: #01696f; color: white; padding: .6rem; border-radius: 6px; text-decoration: none; font-size: .9rem; }
        .empty { text-align: center; padding: 4rem; color: #999; }
        .pagination { margin-top: 2rem; display: flex; justify-content: center; gap: .5rem; }
        .pagination a, .pagination span { padding: .4rem .8rem; border-radius: 5px; border: 1px solid #ddd; text-decoration: none; color: #333; }
        .pagination .active { background: #01696f; color: white; border-color: #01696f; }
    </style>
</head>
<body>
<header>
    <h1>🛍️ Affiliate Deals</h1>
</header>

<div class="container">

    {{-- Filters --}}
    <form class="filters" method="GET">
        <input type="text" name="search" placeholder="Caută produs..." value="{{ request('search') }}">
        <select name="category">
            <option value="">Toate categoriile</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <button type="submit">Filtrează</button>
        @if(request()->hasAny(['search','category']))
            <a href="/products" style="padding:.5rem 1rem;border:1px solid #ddd;border-radius:6px;text-decoration:none;color:#333;">Reset</a>
        @endif
    </form>

    {{-- Product count --}}
    <p style="margin-bottom:1rem;color:#666;">{{ $products->total() }} produse găsite</p>

    {{-- Grid --}}
    @if($products->isEmpty())
        <div class="empty">
            <p style="font-size:2rem;">📦</p>
            <p>Nu există produse încă. Rulează <code>php artisan products:sync-sheet</code></p>
        </div>
    @else
        <div class="grid">
            @foreach($products as $product)
                <div class="card">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                    @else
                        <div style="height:180px;display:flex;align-items:center;justify-content:center;background:#f0f0f0;color:#bbb;font-size:2rem;">📦</div>
                    @endif
                    <div class="card-body">
                        <h3>{{ Str::limit($product->name, 60) }}</h3>
                        <div style="display:flex;align-items:center;flex-wrap:wrap;margin-top:.4rem;">
                            @if($product->price)
                                <span class="price">{{ number_format($product->price, 2) }} {{ $product->currency }}</span>
                            @endif
                            @if($product->old_price)
                                <span class="old-price">{{ number_format($product->old_price, 2) }}</span>
                            @endif
                            @if($product->discount_percent)
                                <span class="discount">-{{ $product->discount_percent }}%</span>
                            @endif
                        </div>
                        <p class="merchant">{{ $product->merchant }}</p>
                        <a href="{{ route('products.redirect', $product->slug) }}" class="btn" target="_blank">
                            Vezi oferta →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="pagination">
            {{ $products->withQueryString()->links('pagination::simple-bootstrap-4') }}
        </div>
    @endif

</div>
</body>
</html>
