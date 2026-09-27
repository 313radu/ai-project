<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} — AI Project</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="font-family:Arial,sans-serif;background:#f6f7fb;margin:0;padding:40px;">
    <div style="max-width:860px;margin:0 auto;">
        <a href="{{ route('home') }}" style="color:#01696f;">← Back to products</a>
        <div style="background:white;border-radius:16px;padding:32px;box-shadow:0 10px 30px rgba(0,0,0,0.08);margin-top:20px;">
            @if($product->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="max-width:300px;border-radius:10px;float:right;margin-left:24px;">
            @endif
            <h1 style="margin-top:0;">{{ $product->name }}</h1>
            <p style="color:#888;">{{ $product->merchant }} · {{ $product->category }}</p>
            <p style="font-size:28px;font-weight:bold;color:#01696f;">{{ $product->price }} {{ $product->currency }}</p>
            @if($product->discount_percent)
                <span style="background:#fee2e2;color:#b91c1c;padding:4px 12px;border-radius:20px;font-size:14px;">-{{ $product->discount_percent }}% OFF</span>
            @endif
            @if($product->description)
                <p style="margin-top:20px;color:#444;">{{ $product->description }}</p>
            @endif
            <a href="{{ route('products.redirect', $product->slug) }}"
               style="display:inline-block;margin-top:24px;padding:14px 28px;background:#111827;color:white;border-radius:10px;text-decoration:none;font-size:16px;">
               Buy Now →
            </a>
        </div>
    </div>
</body>
</html>
