<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CategoryController;

// Endpoint Modul Fondasi (Auth)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);

// Endpoint Modul Campaign
// Publik bisa melihat daftar campaign
Route::get('/campaigns', [CampaignController::class, 'index']);

// Hanya ADMIN yang sudah login yang bisa membuat campaign baru
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::post('/campaigns', [CampaignController::class, 'store']);
});