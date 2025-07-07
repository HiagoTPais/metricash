<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncomeExpenseController;
use App\Http\Controllers\IndicatorsController;
use App\Http\Controllers\CryptoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// });

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Route::get('/home', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/income-expenses', [IncomeExpenseController::class, 'index'])->name('income-expenses.index');
    Route::post('/income-expenses/store', [IncomeExpenseController::class, 'store'])->name('income-expenses.store');
    Route::put('/income-expenses/{income_expense}', [IncomeExpenseController::class, 'update'])->name('income-expenses.update');
    
    Route::get('/indicators', [IndicatorsController::class, 'index'])->name('indicators.index');
    
    // Crypto routes
    Route::get('/crypto', [CryptoController::class, 'index'])->name('crypto.index');
    Route::post('/crypto/wallets', [CryptoController::class, 'createWallet'])->name('crypto.wallets.create');
    Route::post('/crypto/send', [CryptoController::class, 'sendCrypto'])->name('crypto.send');
    Route::get('/crypto/transactions', [CryptoController::class, 'getTransactionHistory'])->name('crypto.transactions');
    Route::get('/crypto/transactions-page', [CryptoController::class, 'transactionsPage'])->name('crypto.transactions.page');
    Route::post('/crypto/convert', [CryptoController::class, 'convertCurrency'])->name('crypto.convert');
    Route::get('/crypto/wallet-address', [CryptoController::class, 'getWalletAddress'])->name('crypto.wallet.address');
});

require __DIR__.'/auth.php';
