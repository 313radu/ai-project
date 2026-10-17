<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Homepage — show all active products
    public function index(Request $request)
    {
        $products = Product::where('is_active', true)
            ->when($request->category, fn ($q) => $q->where('category', $request->category))
            ->when($request->search, fn ($q) => $q->where('name', 'like', '%'.$request->search.'%'))
            ->orderBy('created_at', 'desc')
            ->paginate(24);

        $categories = Product::where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return view('products.index', compact('products', 'categories'));
    }

    // Single product detail page
    public function show(Product $product)
    {
        abort_if(! $product->is_active, 404);

        return view('products.show', compact('product'));
    }

    // Redirect to affiliate link (tracks the click)
    public function redirect(Product $product)
    {
        $url = trim((string) ($product->affiliate_url ?: $product->product_url));

        abort_if($url === '', 404);

        // Add https:// if the sheet link has no scheme (e.g. "emag.ro/produs")
        if (! preg_match('#^https?://#i', $url)) {
            $url = 'https://'.ltrim($url, '/');
        }

        abort_unless(filter_var($url, FILTER_VALIDATE_URL), 404);

        return redirect()->away($url);
    }
}
