<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncomeExpenseController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

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
});

require __DIR__.'/auth.php';
