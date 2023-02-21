<?php

use App\Http\Controllers\AttributesController;
use Illuminate\Support\Facades\Route;

Route::post('store', [AttributesController::class, 'store']);
Route::post('edit', [AttributesController::class, 'edit']);
Route::post('update', [AttributesController::class, 'update']);
