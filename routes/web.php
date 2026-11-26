<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\CategoryController;
use App\Http\Controllers\Owner\UnitController;
use App\Http\Controllers\Owner\StockMovementController;
use App\Http\Controllers\Owner\ShopProductController;
use App\Http\Controllers\Owner\ShopPurchaseController;
use App\Http\Controllers\Owner\SaleController;




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
// Global Product Catalog
Route::get('/product-catalog', [ShopProductController::class, 'catalog'])
    ->name('owner.shop-products.catalog');

Route::post('/product-catalog/claim', [ShopProductController::class, 'claim'])
    ->name('owner.shop-products.claim');

// Shop Products
Route::get('/shop-products', [ShopProductController::class, 'index'])
    ->name('owner.shop-products.index');

Route::get('/shop-products/{shopProduct}', [ShopProductController::class, 'show'])
    ->name('owner.shop-products.show');

Route::delete('/shop-products/{shopProduct}', [ShopProductController::class, 'destroy'])
    ->name('owner.shop-products.destroy');

// Purchase
Route::get('/shop-products/{shopProduct}/purchase', [ShopPurchaseController::class, 'create'])
    ->name('owner.shop-products.purchase');

Route::post('/shop-products/{shopProduct}/purchase', [ShopPurchaseController::class, 'store'])
    ->name('owner.shop-products.purchase.store');

// ======================


// ======================


// ======================


// ======================


// ======================


// ======================


// ======================


// ======================






});


Route::middleware('auth')->prefix('owner')->name('owner.')->group(function () {

    Route::prefix('sales')->name('sales.')->controller(SaleController::class)->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');

        Route::get('/', 'index')->name('index');

        Route::get('/{sale}/invoice', 'invoice')->name('invoice');
        Route::get('/{sale}', 'show')->name('show');
    });


        Route::prefix('purchases')
            ->name('purchases.')
            ->controller(ShopPurchaseController::class)
            ->group(function () {

                Route::get('/', 'index')->name('index');

                Route::get('/create', 'create')->name('create');

                Route::post('/', 'store')->name('store');

                Route::get('/{purchase}', 'show')->name('show');
            });



});



