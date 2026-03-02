<?php

use App\Http\Controllers\BalanceAdjustmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebtorController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentVoucherController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyContextController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Admin\BackupController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Company context
    Route::get('/companies/select', [CompanyContextController::class, 'select'])->name('companies.select');
    Route::post('/companies/switch', [CompanyContextController::class, 'switch'])->name('companies.switch');
    // Redirect GET requests to switch (from bookmarks/direct access) to selection page
    Route::get('/companies/switch', fn() => redirect()->route('companies.select'));

    // Dashboard - Smart routing (admin vs user)
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Home - Debtor Dashboard
    Route::middleware('company')->group(function () {
        Route::get('/debtors', [DebtorController::class, 'index'])->name('debtors.index');
    
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
    
        // Reports - Individual Payment History (scoped by company)
        Route::get('/reports/debtor/{debtor}/payment-history', [ReportController::class, 'debtorPaymentHistory'])->name('reports.debtor.payment-history');

        // Admin-only routes
        Route::middleware('admin')->group(function () {
            Route::resource('users', UserController::class);
            
            // Individual user update endpoints
            Route::put('/users/{user}/basic-info', [UserController::class, 'updateBasicInfo'])->name('users.basic-info.update');
            Route::put('/users/{user}/password', [UserController::class, 'updatePassword'])->name('users.password.update');
            Route::put('/users/{user}/role-permissions', [UserController::class, 'updateRolePermissions'])->name('users.role-permissions.update');
            Route::put('/users/{user}/companies', [UserController::class, 'updateCompanies'])->name('users.companies.update');

            // Company Management (Admin Only)
            Route::resource('companies', CompanyController::class)->except(['show']);
            
            // Session Management (Admin Only)
            Route::get('/sessions', [SessionController::class, 'adminIndex'])->name('sessions.index');
            Route::delete('/sessions/{sessionId}', [SessionController::class, 'destroy'])->name('sessions.destroy');
            
            // Reports Hub (Admin Only)
            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/all-transactions', [ReportController::class, 'allTransactionsPage'])->name('reports.all-transactions');
            Route::get('/reports/all-transactions/download', [ReportController::class, 'downloadAllTransactions'])->name('reports.all-transactions.download');
            
            // Reports Center PDF Downloads
            Route::get('/reports/download/overview', [ReportController::class, 'downloadOverviewPdf'])->name('reports.download.overview');
            Route::get('/reports/download/debtors', [ReportController::class, 'downloadDebtorsPdf'])->name('reports.download.debtors');
            Route::get('/reports/download/outstanding', [ReportController::class, 'downloadOutstandingPdf'])->name('reports.download.outstanding');
            Route::get('/reports/download/payments', [ReportController::class, 'downloadPaymentsPdf'])->name('reports.download.payments');
            
            // Database Backups (Admin Only)
            Route::get('/admin/backups', [BackupController::class, 'index'])->name('admin.backups.index');
            Route::post('/admin/backups/create', [BackupController::class, 'create'])->name('admin.backups.create');
            Route::post('/admin/backups/upload', [BackupController::class, 'upload'])->name('admin.backups.upload');
            Route::get('/admin/backups/{filename}/download', [BackupController::class, 'download'])->name('admin.backups.download');
            Route::post('/admin/backups/{filename}/restore', [BackupController::class, 'restore'])->name('admin.backups.restore');
            Route::delete('/admin/backups/{filename}', [BackupController::class, 'destroy'])->name('admin.backups.destroy');
        });
    });
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

require __DIR__.'/auth.php';
