<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;

//Auth 
Route::controller(AuthController::class)->group(function () {
    Route::post('/number_login', 'numberLogin');
    Route::post('/email_login', 'emailLogin');
    Route::middleware('auth:sanctum')->post('/logout', 'logout');
    Route::post('/register', 'register');
    Route::middleware('auth:sanctum')->post('/verify', 'verify');
    Route::middleware('auth:sanctum')->post('/resend', 'resendCode');
    Route::post('/reset_password_code', 'resetPasswordCode');
    Route::post('/reset_password', 'resetPassword');
});
//users 
Route::middleware('auth:sanctum')->prefix('user')->controller(UserController::class)->group(function () {
    Route::post('/update', 'update');
    Route::post('/favourites', 'favourites');
    Route::post('/favourites/add', 'addFavourite');
    Route::post('/favourites/remove', 'removeFavourite');
    Route::post('/search', 'search');
    Route::post('/addDriver','addDriver');
});
//shops
Route::prefix('shops')->controller(ShopController::class)->group(function () {
    Route::post('/', 'index');
    Route::post('/show', 'show');
    Route::middleware('auth:sanctum')->post('/store', 'store');
});
//Categories
Route::prefix('categories')->controller(CategoryController::class)->group(function () {
    Route::post('/', 'index');
    Route::post('/show', 'show');
    Route::middleware('auth:sanctum')->post('/store', 'store');
});
//products
Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::post('/', 'index');
    Route::post('/show', 'show');
    Route::middleware('auth:sanctum')->post('/store', 'store');
    Route::middleware('auth:sanctum')->post('/update', 'update');
    Route::middleware('auth:sanctum')->post('/destroy', 'destroy');
});
//orders
Route::middleware('auth:sanctum')->prefix('orders')->controller(OrderController::class)->group(function () {
    Route::post('/index', 'index');
    Route::post('/store', 'store');
    Route::post('/cancel', 'cancel');
    Route::post('/show', 'show');
    Route::post('/update_order_status', 'updateOrderStatus');
    Route::post('/pick', 'pick');
    Route::post('/showAcceptedOrders', 'showAcceptedOrders');
    Route::post('/history', 'history');
});
