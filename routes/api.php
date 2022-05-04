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

$freeResourceRoutes = [
    "show",
    "index",
];

Route::resource("/projects", \App\Http\Controllers\ProjectController::class)->only($freeResourceRoutes);

Route::middleware("auth:sanctum")->group(function () use ($freeResourceRoutes) {
    Route::get("/logout", [\App\Http\Controllers\AuthController::class, "logout"]);
    Route::get("/me", [\App\Http\Controllers\AuthController::class, "me"]);
    Route::resource("/projects",
        \App\Http\Controllers\ProjectController::class)->except($freeResourceRoutes)->middleware("role:admin|student");
    Route::get("/projects/{project}/like", [\App\Http\Controllers\ProjectController::class, "like"]);
    Route::get("/myprojects",
        [\App\Http\Controllers\UserController::class, "myProjects"])->middleware("role:admin|student");
    Route::put("/me", [\App\Http\Controllers\UserController::class, "updateProfile"]);
});


