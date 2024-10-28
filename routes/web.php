<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ExchangeRateController;
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


Route::group(['prefix' => 'admin', 'middleware' => 'redirectAdmin'] , function () {
    Route::get('login',[AdminAuthController::class,'showLoginForm'])->name('admin.login');
    Route::post('login',[AdminAuthController::class,'login'])->name('admin.login.post');
    Route::post('logout',[AdminAuthController::class,'logout'])->name('admin.logout');
});

Route::middleware(['auth','admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard',[AdminController::class,'index'])->name('admin.dashboard');
    Route::resource('/currencies', CurrencyController::class);
    // Route::get('/currencies', [CurrencyController::class, 'index'])->name('admin.currencies.index');
    Route::get('/exchangerate', [ExchangeRateController::class, 'index'])->name('admin.exchangerate.index');
    Route::get('/exchangerateedit/{id}', [ExchangeRateController::class, 'edit'])->name('admin.exchangerate.edit');
    Route::get('/exchangerateadd', [ExchangeRateController::class, 'add'])->name('admin.exchangerate.add');
    Route::post('/exchangeratestore', [ExchangeRateController::class, 'store'])->name('exchangeratestore');

});


require __DIR__.'/auth.php';