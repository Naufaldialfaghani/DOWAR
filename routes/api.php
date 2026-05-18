<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\FeedbackController; // 1. Import Controller Distribusi Kamu

// Endpoint Modul Fondasi (Auth)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
}); // Selesai grup auth, kurung penutup liar dan duplikasi login di bawahnya sudah dihapus

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

// Endpoint Modul Feedback
Route::get('/feedbacks', [FeedbackController::class, 'index']);
Route::post('/feedbacks', [FeedbackController::class, 'store']);

// Grup Middleware untuk user yang sudah login
Route::middleware('auth:api')->group(function () {
    // Endpoint Modul Transaksi Donasi
    Route::get('/donations', [DonationController::class, 'index']);
    Route::post('/donations', [DonationController::class, 'store']);

    // 2. Endpoint Modul Distribusi (Haidar)
    // Menampilkan riwayat penyaluran barang (Read)
    Route::get('/distributions', [DistributionController::class, 'index']);
    // Mencatat log pengiriman barang donasi baru (Create)
    Route::post('/distributions', [DistributionController::class, 'store']);
}); // Kurung kurawal ganda yang di bawah ini sebelumnya sudah dibersihkan