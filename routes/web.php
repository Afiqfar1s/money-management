<?php

use App\Http\Controllers\BalanceAdjustmentController;
use App\Http\Controllers\DebtorController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentVoucherController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Home - Debtor Dashboard
    Route::get('/', [DebtorController::class, 'index'])->name('debtors.index');
    Route::get('/dashboard', [DebtorController::class, 'index'])->name('dashboard');
    
    // Debtor Management
    Route::get('/debtors/create', [DebtorController::class, 'create'])->name('debtors.create');
    Route::post('/debtors', [DebtorController::class, 'store'])->name('debtors.store');
    Route::get('/debtors/{debtor}', [DebtorController::class, 'show'])->name('debtors.show');
    Route::get('/debtors/{debtor}/edit', [DebtorController::class, 'edit'])->name('debtors.edit');
    Route::put('/debtors/{debtor}', [DebtorController::class, 'update'])->name('debtors.update');
    Route::delete('/debtors/{debtor}', [DebtorController::class, 'destroy'])->name('debtors.destroy');
    
    // Payments
    Route::post('/debtors/{debtor}/payments', [PaymentController::class, 'store'])->name('payments.store');
    
    // Balance Adjustments
    Route::post('/debtors/{debtor}/adjustments', [BalanceAdjustmentController::class, 'store'])->name('adjustments.store');
    
    // Refresh Balance
    Route::post('/debtors/{debtor}/refresh', [DebtorController::class, 'refresh'])->name('debtors.refresh');
    Route::post('/debtors/refresh', [DebtorController::class, 'refreshAll'])->name('debtors.refreshAll');
    
    // Payment Voucher
    Route::get('/payments/{payment}/voucher', [PaymentVoucherController::class, 'show'])->name('payments.voucher');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Admin-only routes
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
        
        // Session Management (Admin Only)
        Route::get('/sessions', [SessionController::class, 'adminIndex'])->name('sessions.index');
        Route::delete('/sessions/{sessionId}', [SessionController::class, 'destroy'])->name('sessions.destroy');
    });
});

require __DIR__.'/auth.php';
