<?php

use App\Models\Product;
use App\Models\ShippingPrice;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ShippingPriceController;

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
Route::get('', [ProductController::class, 'index']);
// Route::get('cart', [ProductController::class, 'cart']);

//category
// Route::resource('category', CategoryController::class);
Route::get('category/{slug}', [CategoryController::class, 'showProduct']);
Route::get('category', [CategoryController::class, 'index']);

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
Route::post('checkout-distince', [CheckoutController::class, 'distancePost']);
// Route::get('checkout-distince/{latFrom}/{longFrom}/{latTo}/{longTo}', [CheckoutController::class, 'distance']);
// Route::get('checkout-distince', [CheckoutController::class, 'distance']);

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

// shipping price
Route::resource('shipping-price', ShippingPriceController::class)->middleware('auth');
Route::post('update-shipping-max', [ShippingPriceController::class, 'updateShippingMax'])->middleware('auth');

// setting
Route::resource('setting', SettingController::class)->middleware('auth');

Route::put('setting-location', [SettingController::class, 'updateLocation'])->middleware('auth');
Route::get('setting-location', [SettingController::class, 'location'])->middleware('auth');

Route::put('setting-payment', [SettingController::class, 'updatePayment'])->middleware('auth');
Route::get('setting-payment', [SettingController::class, 'payment'])->middleware('auth');

Route::put('setting-shop', [SettingController::class, 'updateShop'])->middleware('auth');
Route::get('setting-shop', [SettingController::class, 'shop'])->middleware('auth');

Route::put('setting-email', [SettingController::class, 'updateEmail'])->middleware('auth');
Route::get('setting-email', [SettingController::class, 'email'])->middleware('auth');

Route::put('setting-delivery', [SettingController::class, 'updateDelivery'])->middleware('auth');
Route::get('setting-delivery', [SettingController::class, 'delivery'])->middleware('auth');

Route::put('setting-payment-type', [SettingController::class, 'updatePaymentType'])->middleware('auth');
Route::get('setting-payment-type', [SettingController::class, 'paymentType'])->middleware('auth');

// Route::put('setting-payment-shipping', [SettingController::class, 'updatePaymentShipping'])->middleware('auth');
// Route::get('setting-payment-shipping', [SettingController::class, 'paymentShipping'])->middleware('auth');

// grouping
// Route::controller(ProductController::class)->group(function () {
//     Route::get('', 'index');
//     // Route::get('/product/{id}', 'index');
//     // Route::post('/orders', 'store');
// });
