<?php

use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

Route::post('/verify', [VerificationController::class, 'verify']);
Route::get('/history', [VerificationController::class, 'history']);
