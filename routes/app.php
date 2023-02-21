<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppRegistrationController;

// basic routes for app registration
Route::post('app/register', [AppRegistrationController::class, 'register']);
