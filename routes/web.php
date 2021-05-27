<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum', 'verified'])->group(function() {
	Route::get("/tasks", [TaskController::class,"getAll"]);
	Route::post("/tasks", [TaskController::class,"create"]);
	Route::delete("/tasks/{id}", [TaskController::class,"delete"]);
	Route::get("/tasks/{id}", [TaskController::class,"getTaskById"]);
	Route::put("/tasks/{id}", [TaskController::class,"update"]);
});

require __DIR__.'/auth.php';