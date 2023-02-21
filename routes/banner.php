<?php

use App\Http\Controllers\BannerController;
use Illuminate\Support\Facades\Route;
// prefix banner
Route::post('create', [BannerController::class, 'createBanner']);
Route::post('delete', [BannerController::class, 'deleteBanner']);
Route::get('get/all', [BannerController::class, 'getAllBanner']);
