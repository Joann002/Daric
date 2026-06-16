<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\RecurringTransactionController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\ExportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Comptes
    Route::resource('accounts', AccountController::class);
    
    // Catégories
    Route::resource('categories', CategoryController::class)->except(['show']);
    
    // Transactions
    Route::resource('transactions', TransactionController::class);
    
    // Transferts
    Route::resource('transfers', TransferController::class)->except(['show', 'edit', 'update']);
    
    // Budgets
    Route::resource('budgets', BudgetController::class);
    
    // Transactions récurrentes
    Route::resource('recurring-transactions', RecurringTransactionController::class);
    
    // Dettes
    Route::resource('debts', DebtController::class);
    
    // Export
    Route::get('/export/transactions/csv', [ExportController::class, 'exportTransactionsCsv'])->name('export.transactions.csv');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

