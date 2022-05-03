<?php

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

Route::get("/", function () {
    return response()->json([
        "message" => "Welcome to the API"
    ]);
});

// auth routes
Route::post("/register", [\App\Http\Controllers\AuthController::class, "register"]);
Route::post("/login", [\App\Http\Controllers\AuthController::class, "login"]);

Route::middleware("auth:sanctum")->group(function () {
    Route::get("/logout", [\App\Http\Controllers\AuthController::class, "logout"]);
    Route::get("/me", [\App\Http\Controllers\AuthController::class, "me"]);
});


