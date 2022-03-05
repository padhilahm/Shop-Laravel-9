<?php

use App\Http\Controllers\BuyerController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('home.index');
// });

Route::resource('product', ProductController::class);
// Route::get('cart', [ProductController::class, 'cart']);

// cart
Route::get('cart', [ProductController::class, 'cart'])->name('cart');
Route::post('add-to-cart', [ProductController::class, 'addToCart'])->name('add.to.cart');
// Route::get('add-to-cart/{id}', [ProductController::class, 'addToCart'])->name('add.to.cart');
Route::patch('update-cart', [ProductController::class, 'update'])->name('update.cart');
Route::delete('remove-from-cart', [ProductController::class, 'remove'])->name('remove.from.cart');

// checkout
Route::get('checkout-buyer', [CheckoutController::class, 'checkoutBuyer']);
// Route::post('checkout-buyer', [CheckoutController::class, 'storeBuyer']);
Route::post('snap-token', [CheckoutController::class, 'token']);
Route::post('snap-finish', [CheckoutController::class, 'finish']);
// check transaction
Route::get('transaction-check', [CheckoutController::class, 'checkTransaction']);
Route::post('transaction-check', [CheckoutController::class, 'check']);


// login
Route::get('login', [LoginController::class, 'index'])->name('login')->middleware('guest');;
Route::get('logout', [LoginController::class, 'logout']);
Route::post('login', [LoginController::class, 'authenticate']);

// dashboard
Route::get('dashboard', [DashboardController::class, 'index'])->middleware('auth');

// payment
Route::resource('payments', PaymentController::class)->middleware('auth');

// product
Route::resource('products', ProductsController::class)->middleware('auth');

// categories
Route::resource('categories', CategoriesController::class)->middleware('auth');

// buyer
Route::resource('buyers', BuyerController::class)->middleware('auth');

// users
Route::resource('users', UsersController::class)->middleware('auth');

// setting
Route::resource('setting', SettingController::class)->middleware('auth');
Route::put('setting-update', [SettingController::class, 'updateSetting'])->middleware('auth');

// grouping
Route::controller(ProductController::class)->group(function () {
    Route::get('', 'index');
    // Route::get('/product/{id}', 'index');
    // Route::post('/orders', 'store');
});
