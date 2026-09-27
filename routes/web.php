<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\ProductController;
use App\Models\Product;

// Homepage — passes latest active products to welcome.blade.php
Route::get('/', function () {
    $products = Product::where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->limit(12)
        ->get();
    return view('welcome', compact('products'));
})->name('home');

// Products
Route::get('/products',                   [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}',    [ProductController::class, 'show'])->name('products.show');
Route::get('/go/{product:slug}',          [ProductController::class, 'redirect'])->name('products.go');

Route::post('/ai-chat', [AiChatController::class, 'ask'])->name('ai.chat');
