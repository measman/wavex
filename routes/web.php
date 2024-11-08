<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\BankTransactionController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DailyStatusController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Models\BankTransaction;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


Route::group(['prefix' => 'admin', 'middleware' => 'redirectAdmin'], function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::resource('/currencies', CurrencyController::class);
    Route::resource('/exchangerate', ExchangeRateController::class);
    Route::resource('/wallets', WalletController::class);
    Route::resource('/banktransactions', BankTransactionController::class);
    Route::resource('/user', UserController::class);
    Route::resource('/transactions',TransactionController::class);
    Route::post('/transactions/{transaction}/updatetransaction', [TransactionController::class, 'updatetransaction'])
    ->name('transactions.updatetransaction');
    Route::resource('/dailystatus', DailyStatusController::class);
    Route::post('/dailystatus/{id}', [DailyStatusController::class, 'insertstatus'])->name('dailystatus.insertstatus');


    Route::resource('/settings', SettingsController::class);
});


require __DIR__ . '/auth.php';