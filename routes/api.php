<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and are assigned the "api"
| middleware group. Enjoy building your API!
|
*/

// TEST POSTMAN & BACKEND API
Route::get('ping', function () {
    return response()->json(['message' => 'API is working!']);
});

// OPEN ROUTES
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('forgotPassword', [AuthController::class, 'forgotPassword']);
Route::prefix('Auth')->middleware('auth:api')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('changePassword', [AuthController::class, 'changePassword']);
    // Route::post('resetPassword', [AuthController::class, 'resetPassword']);
    Route::post('logout', [AuthController::class, 'logout']);
});
