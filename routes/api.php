<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\DivisionController;

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

//Division Routes
Route::apiResource('/divisions',DivisionController::class);

// TEAMS ROUTES 
Route::apiResource('/teams', TeamController::class);
Route::post('teams/edit/{id}', [TeamController::class, 'update']);

// PLAYER ROUTES 
Route::apiResource('/players', PlayerController::class);

// GAME ROUTES 
Route::apiResource('/games', GameController::class);
