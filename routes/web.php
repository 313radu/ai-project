<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiChatController;

Route::view('/', 'welcome');

Route::post('/ai-chat', [AiChatController::class, 'ask'])->name('ai.chat');
