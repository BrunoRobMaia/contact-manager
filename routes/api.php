<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\ContactController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
  Route::post('/register', [AuthController::class, 'register']);
  Route::post('/login',  [AuthController::class, 'login']);
  Route::middleware('auth:sanctum')->group(function () {
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::put('/contacts/{contact}', [ContactController::class, 'update']);
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
  });
});
