<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductItemController;
use App\Models\ProductItem;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $productItems = ProductItem::all();
    return view('catalog', ['productItems' => $productItems]);
});

// ADMIN ROUTES

Route::get('/admin', function () {
    $productItems = ProductItem::all();
    return view('admin', ['productItems' => $productItems]);
});

Route::post('/admin', [ProductItemController::class, 'store']);

Route::delete('/admin/{id}', [ProductItemController::class, 'destroy'])->name('deleteProduct');

Route::get('/admin/{id}/edit', [ProductItemController::class, 'edit'])->name('editProduct');

Route::put('/admin/{id}', [ProductItemController::class, 'update'])->name('updateProduct');

// END ADMIN ROUTES

// CART ROUTES

Route::get('/cart', [CartController::class, 'index'])->name('cart');

Route::get('/cart/add/{id}', [CartController::class, 'add']);

Route::post('/cart/confirm', [CartController::class, 'confirm']);

Route::put('/cart', [CartController::class, 'update']);

// END CART ROUTES

// ORDER ROUTES

Route::get('/order/{id}/confirm', [OrderController::class, 'confirm'])->name('order.confirm');

// END ORDER ROUTES

// AUTH ROUTES

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
});

// END AUTH ROUTES

// START USER ROUTES

//Route::group(['middleware' => 'admin', 'prefix' => 'api'], function () {
//    Route::get('/users', 'UserController@index');
//    Route::post('/users', 'UserController@store');
//    Route::get('/users/{id}', 'UserController@show');
//    Route::put('/users/{id}', 'UserController@update');
//    Route::delete('/users/{id}', 'UserController@destroy');
//});

// END USER ROUTES
