<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\ProductController;

Route::view('/', 'welcome');

// Products
Route::get('/products',          [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/go/{product:slug}', [ProductController::class, 'redirect'])->name('products.redirect');

Route::post('/ai-chat', [AiChatController::class, 'ask'])->name('ai.chat');
