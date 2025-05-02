<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;

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

// check if the user is authenticated
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// OPEN ROUTES
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register']);
Route::post('forgotPassword', [AuthController::class, 'forgotPassword']);

// AUTHENTICATED ROUTES
Route::prefix('Auth')->middleware('auth:api')->group(function () {
    Route::post('changePassword', [AuthController::class, 'changePassword']);
    // Route::post('resetPassword', [AuthController::class, 'resetPassword']);
    Route::post('logout', [AuthController::class, 'logout']);
});

// TEAMS ROUTES 
Route::middleware('auth:api')->apiResource('/teams', TeamController::class);
