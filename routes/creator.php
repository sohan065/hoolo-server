<?php

use App\Http\Controllers\CreatorController;
use Illuminate\Support\Facades\Route;

//prefix creator
Route::post('registration', [CreatorController::class, 'creatorRegistration']);
Route::post('verification', [CreatorController::class, 'verification']);
Route::post('resend-email-verification', [CreatorController::class, 'resendEmailVerification']);
Route::post('resend-phone-verification', [CreatorController::class, 'resendPhoneVerification']);

Route::post('login', [CreatorController::class, 'creatorLogin']);
Route::post('login-success', [CreatorController::class, 'creatorLoginSuccess']);
