<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\CategoryController;
use App\Http\Controllers\Owner\UnitController;
use App\Http\Controllers\Owner\StockMovementController;


Route::get('/', function () {
    return view('portal');
})->name('portal.home');

Route::middleware('guest')->group(function () {

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.submit');

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');

});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('owner.dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');



Route::get('/categories', [CategoryController::class, 'index'])
    ->name('owner.categories.index');

Route::post('/categories', [CategoryController::class, 'store'])
    ->name('owner.categories.store');

Route::put('/categories/{category}', [CategoryController::class, 'update'])
    ->name('owner.categories.update');

Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
    ->name('owner.categories.destroy');


Route::get('/units', [UnitController::class, 'index'])
    ->name('owner.units.index');

Route::post('/units', [UnitController::class, 'store'])
    ->name('owner.units.store');

Route::put('/units/{unit}', [UnitController::class, 'update'])
    ->name('owner.units.update');

Route::delete('/units/{unit}', [UnitController::class, 'destroy'])
    ->name('owner.units.destroy');

// ======================// Stock Movement
Route::get('/stock-movements', [StockMovementController::class, 'index'])
    ->name('owner.stock-movements.index');

Route::post('/stock-movements/adjustment', [StockMovementController::class, 'storeAdjustment'])
    ->name('owner.stock-movements.adjustment');
// ======================


// ======================


// ======================


// ======================


// ======================


// ======================


// ======================


// ======================


// ======================






});