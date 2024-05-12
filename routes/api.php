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

// auth
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// products
Route::get('/catalog', [ProductItemController::class, 'index']);

// cart
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add/{id}', [CartController::class, 'add']);

// authorization users
Route::group(['middleware' => ['auth:sanctum']], function () {
    // logout user
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // order confirm
    Route::post('/order/confirm', [OrderController::class, 'confirm']);
});

// authorization admin
Route::group(['middleware' => ['auth:sanctum', 'admin']], function () {
    // users admin
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // products admin
    Route::post('/catalog', [ProductItemController::class, 'store']);
    Route::get('/catalog/{id}', [ProductItemController::class, 'show']);
    Route::post('/catalog/{id}/edit', [ProductItemController::class, 'update']);
    Route::delete('/catalog/{id}', [ProductItemController::class, 'destroy']);
});
