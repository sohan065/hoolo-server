<?php



use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;

use App\Http\Controllers\MerchantController;

use App\Http\Controllers\RegistrationController;



//prefix merchant

Route::post('registration', [RegistrationController::class, 'merchantReg']);

Route::post('verification', [RegistrationController::class, 'verification']);

Route::post('resend-verification', [RegistrationController::class, 'resendVerification']);

Route::post('profile', [MerchantController::class, 'profile']);

Route::post('login', [LoginController::class, 'merchantLogin']);

Route::post('forget-password', [LoginController::class, 'forgetPassword']);

Route::post('reset-password', [LoginController::class, 'resetPassword']);

Route::post('change-password', [LoginController::class, 'changePassword']);

Route::get('info', [MerchantController::class, 'getMerchantInfo']);

Route::get('posts', [MerchantController::class, 'getAllPosts']);

Route::get('products', [MerchantController::class, 'getAllProducts']);
Route::get('products/all', [MerchantController::class, 'getAllProductsByToken']);
Route::get('products/gallery', [MerchantController::class, 'getAllProductsGalleryByToken']);

Route::post('sendMessage', [LoginController::class, 'sendMessage']);

Route::post('edit', [MerchantController::class, 'editMerchantInfo']);
