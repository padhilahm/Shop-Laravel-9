<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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

// login
Route::get('login', [LoginController::class, 'index'])->name('login')->middleware('guest');;
Route::get('logout', [LoginController::class, 'logout']);
Route::post('login', [LoginController::class, 'authenticate']);

// dashboard
Route::get('dashboard', [DashboardController::class, 'index'])->middleware('auth');

// product
Route::resource('products', ProductsController::class)->middleware('auth');

// categories
Route::resource('categories', CategoriesController::class)->middleware('auth');

// users
Route::resource('users', UsersController::class)->middleware('auth');

// grouping
Route::controller(ProductController::class)->group(function () {
    Route::get('', 'index');
    // Route::get('/product/{id}', 'index');
    // Route::post('/orders', 'store');
});
