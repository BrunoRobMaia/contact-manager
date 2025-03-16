<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\ContactController;
use App\Http\Controllers\api\v1\ExportController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
  Route::post('/register', [AuthController::class, 'register']);
  Route::post('/login',  [AuthController::class, 'login']);
  Route::middleware('auth:sanctum')->group(function () {
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::get('/export-excel', [ExportController::class, 'export'])->name('export.excel');
    Route::put('/contacts/{contact}', [ContactController::class, 'update']);
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
  });
});
