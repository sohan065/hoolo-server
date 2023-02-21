<?php

use App\Http\Controllers\LiveroomController;
use Illuminate\Support\Facades\Route;

Route::post('create', [LiveroomController::class, 'create']);

Route::post('creator/verification', [LiveroomController::class, 'creatorVerification']);
Route::post('creator/verify', [LiveroomController::class, 'creatorVerify']);
Route::get('course/{uuid}', [LiveroomController::class, 'courses']);

Route::post('phone/verification', [LiveroomController::class, 'phoneVerification']);
Route::post('phone/verify', [LiveroomController::class, 'phoneVerify']);
