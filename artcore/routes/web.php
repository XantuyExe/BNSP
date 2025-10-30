<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{HomeController, UnitController, RentalController, ProfileController};
use App\Http\Controllers\Admin\{DashboardController, CategoryAdminController, UnitAdminController, UserAdminController, RentalAdminController, ReturnAdminController};
use App\Http\Middleware\EnsureUserIsAdmin;


Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/units', [UnitController::class, 'index'])->name('units.index');
    Route::get('/units/{unit}', [UnitController::class, 'show'])->name('units.show');

    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
    Route::post('/rentals/{rental}/purchase', [RentalController::class, 'purchase'])->name('rentals.purchase');
    Route::post('/rentals/{rental}/return-request', [RentalController::class, 'requestReturn'])->name('rentals.return-request');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/cart', [RentalController::class,'cart'])->name('cart');           // Keranjang
    Route::post('/cart/add', [RentalController::class,'addToCart'])->name('cart.add');
    Route::delete('/cart/{unit}', [RentalController::class,'removeFromCart'])->name('cart.remove');
    Route::get('/purchases', [RentalController::class,'purchases'])->name('purchases'); // Riwayat pembelian
});

Route::middleware(['auth', EnsureUserIsAdmin::class])
    ->prefix('admin-manage')
    ->name('adminManage.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('units', UnitAdminController::class);
        Route::resource('categories', CategoryAdminController::class);
        Route::resource('users', UserAdminController::class);
        Route::resource('rentals', RentalAdminController::class)->only(['index','show']);
        Route::get('reports/rentals', [RentalAdminController::class,'history'])->name('reports.rentals');
        Route::get('reports/rentals/export', [RentalAdminController::class,'exportHistory'])->name('reports.rentals.export');
        Route::get('returns', [ReturnAdminController::class,'index'])->name('returns.index');
        Route::post('returns/{rental}/confirm', [ReturnAdminController::class,'confirm'])->name('returns.confirm');
        Route::get('returns/{rental}/confirm', [ReturnAdminController::class,'form'])->name('returns.form');
});

require __DIR__.'/auth.php';
