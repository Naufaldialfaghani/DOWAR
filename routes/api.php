<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\DonationController;

// Endpoint Modul Fondasi (Auth)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});
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

// Endpoint Modul Penerima Manfaat (Beneficiaries)
Route::get('/beneficiaries', [BeneficiaryController::class, 'index']);
Route::post('/beneficiaries', [BeneficiaryController::class, 'store']);

Route::middleware('auth:api')->group(function () {
    // Endpoint Modul Transaksi Donasi
    Route::get('/donations', [DonationController::class, 'index']);
    Route::post('/donations', [DonationController::class, 'store']);
});
});
