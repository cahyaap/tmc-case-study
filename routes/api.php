<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api.key')->group(function () {
    Route::post('/categories', [CategoryController::class, "store"])->name('categories.store');
    Route::post('/products', [ProductController::class, "store"])->name('products.store');
    Route::get('/search', [ProductController::class, "search"])->name('search');
});
