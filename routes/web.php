<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\ContactController;

Route::get('/', function () {
  return view('login');
});
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/contacts', [ContactController::class, 'showContactsPage'])->name('contacts');
