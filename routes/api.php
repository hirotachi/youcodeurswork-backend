<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
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
Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

$freeResourceRoutes = [
    "show",
    "index",
];

Route::resource("/projects", ProjectController::class)->only($freeResourceRoutes);
Route::resource("/jobs", JobController::class)->only($freeResourceRoutes);

Route::middleware("auth:sanctum")->group(function () use ($freeResourceRoutes) {
    Route::get("/logout", [AuthController::class, "logout"]);
    Route::get("/me", [AuthController::class, "me"]);
    Route::put("/me", [UserController::class, "updateProfile"]);


    $resources = [
        "/projects" => ProjectController::class,
        "/jobs" => JobController::class,
    ];

    foreach ($resources as $resource => $controller) {
        Route::resource($resource, $controller)
            ->except($freeResourceRoutes)->middleware("role:admin|".($resource === "/projects" ? ProjectController::$role : JobController::$role));
    }


    Route::get("/projects/{project}/like", [ProjectController::class, "like"]);

    $myRoutes = [
        "projects",
        "jobs",
    ];
    foreach ($myRoutes as $route) {
        Route::get("/me/$route", [
            UserController::class, $route
        ])->middleware("role:admin|".($route === "projects" ? ProjectController::$role : JobController::$role));
    }
});


