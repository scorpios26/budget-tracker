<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    // If not admin, use the controller for user dashboard
    return app(\App\Http\Controllers\DashboardController::class)->index();
})->middleware(['auth', 'verified'])->name('dashboard');
    Route::post('/budget', [BudgetController::class, 'store'])->name('budget.store');
    Route::delete('/budget/{id}', [BudgetController::class, 'destroy'])->name('budget.destroy');

    Route::post('/expense', [ExpenseController::class, 'store'])->name('expense.store');
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    Route::delete('/category/{category}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('category.destroy');

    Route::put('/expense/{id}', [ExpenseController::class, 'update'])->name('expense.update');
    Route::delete('/expense/{id}', [ExpenseController::class, 'destroy'])->name('expense.destroy');

    Route::get('/expenses/export', [ExpenseController::class, 'exportCsv'])->name('expenses.exportCsv');

    Route::get('/expenses/chart', [\App\Http\Controllers\ExpenseController::class, 'chartPage'])->name('expenses.chart');
    Route::get('/expenses/chart-data', [\App\Http\Controllers\ExpenseController::class, 'chartData'])->name('expenses.chart.data');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Dashboard
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    // Add more admin routes here as needed
});

require __DIR__.'/auth.php';
