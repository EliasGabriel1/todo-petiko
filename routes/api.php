<?php

use App\Http\Controllers\TodoController;
use App\Http\Controllers\TodoTypeController;
use App\Http\Controllers\AuthAdapterController;
use Illuminate\Support\Facades\Route;

Route::apiResource('todo-types', TodoTypeController::class);
Route::apiResource('todo-types.todos', TodoController::class);

// Auth adapter endpoints (stateless API)
Route::post('auth/register', [AuthAdapterController::class, 'register']);
Route::post('auth/login', [AuthAdapterController::class, 'login']);
Route::get('auth/me', [AuthAdapterController::class, 'me']);
// simple ping for debugging route registration
Route::get('auth/ping', function () {
	return response()->json(['ok' => true]);
});
