<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', [UserController::class, 'index']);

/* Profile Management */
Route::post('profile/update-picture', [ProfileController::class, 'update_picture']);
Route::post('/profile/update-name', [ProfileController::class, 'update_name']);
Route::post('/profile/update-email', [ProfileController::class, 'update_email']);

/* Users Management */
Route::post('/register', [UserController::class, 'store']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users/update/', [UserController::class, 'update']);
Route::put('/users/reset-password/{id}', [UserController::class, 'reset_password']);
Route::delete('/users/delete/{id}', [UserController::class, 'destroy']);