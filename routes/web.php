<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessUnitController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\PayableController;
use App\Http\Controllers\ReceivableController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('dashboard.statistics');

    // Cash Management
    Route::get('/cash/daily-report', [CashController::class, 'dailyReport'])->name('cash.daily-report');
    Route::post('/cash/{cash}/submit', [CashController::class, 'submit'])->name('cash.submit');
    Route::post('/cash/{cash}/approve', [CashController::class, 'approve'])->name('cash.approve');
    Route::post('/cash/{cash}/reject', [CashController::class, 'reject'])->name('cash.reject');
    Route::resource('cash', CashController::class);

    // Income Transactions
    Route::post('/income/{income}/submit', [IncomeController::class, 'submit'])->name('income.submit');
    Route::post('/income/{income}/approve', [IncomeController::class, 'approve'])->name('income.approve');
    Route::post('/income/{income}/reject', [IncomeController::class, 'reject'])->name('income.reject');
    Route::resource('income', IncomeController::class);

    // Expense Transactions
    Route::post('/expense/{expense}/submit', [ExpenseController::class, 'submit'])->name('expense.submit');
    Route::post('/expense/{expense}/approve', [ExpenseController::class, 'approve'])->name('expense.approve');
    Route::post('/expense/{expense}/reject', [ExpenseController::class, 'reject'])->name('expense.reject');
    Route::resource('expense', ExpenseController::class);

    // Receivables (Piutang)
    Route::get('/receivable/aging', [ReceivableController::class, 'agingReport'])->name('receivable.aging');
    Route::post('/receivable/{receivable}/payment', [ReceivableController::class, 'addPayment'])->name('receivable.payment');
    Route::resource('receivable', ReceivableController::class);

    // Payables (Hutang)
    Route::post('/payable/{payable}/payment', [PayableController::class, 'addPayment'])->name('payable.payment');
    Route::resource('payable', PayableController::class);

    // Assets
    Route::get('/asset/categories', [AssetController::class, 'categories'])->name('asset.categories');
    Route::post('/asset/categories', [AssetController::class, 'storeCategory'])->name('asset.categories.store');
    Route::post('/asset/{asset}/dispose', [AssetController::class, 'dispose'])->name('asset.dispose');
    Route::resource('asset', AssetController::class);

    // Business Units
    Route::post('/business-unit/{businessUnit}/toggle', [BusinessUnitController::class, 'toggleActive'])->name('business-unit.toggle');
    Route::resource('business-unit', BusinessUnitController::class);

    // Reports
    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/income-statement', [ReportController::class, 'incomeStatement'])->name('income-statement');
        Route::get('/balance-sheet', [ReportController::class, 'balanceSheet'])->name('balance-sheet');
        Route::get('/cash-flow', [ReportController::class, 'cashFlow'])->name('cash-flow');
        Route::get('/general-ledger', [ReportController::class, 'generalLedger'])->name('general-ledger');
        Route::get('/trial-balance', [ReportController::class, 'trialBalance'])->name('trial-balance');
    });

    // User Management (Director only)
    Route::middleware('can:manageUsers')->group(function () {
        Route::put('/user/{user}/password', [UserController::class, 'updatePassword'])->name('user.password');
        Route::post('/user/{user}/toggle', [UserController::class, 'toggleActive'])->name('user.toggle');
        Route::resource('user', UserController::class);
    });
});
