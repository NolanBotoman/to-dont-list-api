<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post("/auth/login", [AuthController::class,"login"]);
Route::post("/auth/register", [AuthController::class,"register"]);

Route::middleware(['auth:sanctum', 'verified'])->group(function() {
	Route::post("/auth/me", [AuthController::class,"getUserData"]);
	Route::post("/auth/logout", [AuthController::class,"logout"]);
});

Route::any("/error", [AuthController::class,"default"])->name("login");