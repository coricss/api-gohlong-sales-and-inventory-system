<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\LogsController;
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
/* group middleware */
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/users', [UserController::class, 'index']);

    /* Profile Management */
    Route::post('profile/update-picture', [ProfileController::class, 'update_picture']);
    Route::post('/profile/update-name', [ProfileController::class, 'update_name']);
    Route::post('/profile/update-email', [ProfileController::class, 'update_email']);
    Route::post('/profile/change-password', [ProfileController::class, 'change_password']);

    /* Dashboard */
    Route::get('/widgets', [DashboardController::class, 'index']);
    Route::get('/weekly-sales', [DashboardController::class, 'getWeeklySales']);
    Route::get('/top-products', [DashboardController::class, 'getTopProducts']);

    /* Users Management */
    Route::post('/register', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users/update/', [UserController::class, 'update']);
    Route::put('/users/reset-password/{id}', [UserController::class, 'reset_password']);
    Route::delete('/users/delete/{id}', [UserController::class, 'destroy']);

    /* Inventory Management */


    //Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/new-category', [CategoryController::class, 'store']);
    Route::get('/category/{id}', [CategoryController::class, 'show']);
    Route::put('/update-category/{id}', [CategoryController::class, 'update']);
    Route::delete('/delete-category/{id}', [CategoryController::class, 'destroy']);

    // Brands
    Route::get('/brands', [BrandsController::class, 'index']);
    Route::post('/new-brand', [BrandsController::class, 'store']);
    Route::get('/brand/{id}', [BrandsController::class, 'show']);
    Route::put('/update-brand/{id}', [BrandsController::class, 'update']);
    Route::delete('/delete-brand/{id}', [BrandsController::class, 'destroy']);

    // Products
    Route::get('/products', [ProductsController::class, 'index']);
    Route::post('/new-product', [ProductsController::class, 'store']);
    Route::get('/product/{id}', [ProductsController::class, 'show']);
    Route::get('/product-code/{product_code}', [ProductsController::class, 'product_code']);
    Route::put('/update-product/{id}', [ProductsController::class, 'update']);
    Route::delete('/delete-product/{id}', [ProductsController::class, 'destroy']);
    Route::get('/print-barcode/{id}', [ProductsController::class, 'print_barcode']);
    Route::put('/update-actual-stocks/{id}', [ProductsController::class, 'update_actual_stocks']);

    /* SALES */
    Route::get('/count-sales', [SalesController::class, 'count_sales_today']);
    Route::get('/sales', [SalesController::class, 'index']);
    Route::post('/new-sale', [SalesController::class, 'store']);
    Route::post('/revert-transaction', [SalesController::class, 'revert_transaction']);

    /* LOGS */
    Route::get('/logs', [LogsController::class, 'index']);
    Route::post('/new-log', [LogsController::class, 'store']);
});