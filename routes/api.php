<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



// products
Route::get('/catalog', [ProductItemController::class, 'index']);
Route::get('/catalog/{id}', [ProductItemController::class, 'show']);

// cart
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add/{id}', [CartController::class, 'addToCartProduct']);
Route::post('/cart/remove/{id}', [CartController::class, 'removeFromCartProduct']);

// auth
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// user
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users/{id}', [UserController::class, 'update']);

// orders
Route::get('/orders', [OrderController::class, 'index']);
Route::post('/order/confirm', [OrderController::class, 'confirm']);

// authorization users
Route::group(['middleware' => ['auth:sanctum']], function () {
    // logout user
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

// authorization admin
Route::group(['middleware' => ['admin']], function () {
    // users admin
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // products admin
    Route::post('/catalog', [ProductItemController::class, 'store']);
    Route::post('/catalog/{id}/edit', [ProductItemController::class, 'update']);
    Route::delete('/catalog/{id}', [ProductItemController::class, 'destroy']);

    // orders admin
    Route::post('/order/{id}/edit', [OrderController::class, 'update']);

});
