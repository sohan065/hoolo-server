<?php

use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;

// prefix super admin
Route::post('store', [SuperAdminController::class, 'createSuperAdmin']);
