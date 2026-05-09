<?php

use App\Http\Controllers\AuthAdapterController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\TodoTypeController;
use Illuminate\Support\Facades\Route;

// Route::prefix('api')->group(function () {
//     Route::apiResource('todo-types', TodoTypeController::class);
//     Route::apiResource('todo-types.todos', TodoController::class);
// });

Route::get('/', function () {
    return view('welcome');
});
