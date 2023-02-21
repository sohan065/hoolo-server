<?php



use App\Http\Controllers\CourseController;

use App\Http\Controllers\ProductController;

use App\Http\Controllers\UserController;

use Illuminate\Support\Facades\Route;



//prefix user
Route::post('store', [UserController::class, 'createUser']);
Route::post('verify', [UserController::class, 'userVerification']);

Route::post('info/store', [UserController::class, 'infoStore']);
Route::post('info/edit', [UserController::class, 'editInfo']);
Route::post('info/update', [UserController::class, 'updateInfo']);
Route::post('info/delete', [UserController::class, 'deleteInfo']);

Route::post('profile/store', [UserController::class, 'profileStore']);
Route::post('profile/delete', [UserController::class, 'deleteProfile']);
Route::get('all/info', [UserController::class, 'getAllUserInfo']);
Route::get('info', [UserController::class, 'getUserInfo']);

// log out 
Route::get('logout', [UserController::class, 'logout']);

// get all course by user uuid
Route::get('courses', [CourseController::class, 'getAllUserCourse']);

// get all product orders by user uuid
Route::get('product/orders', [UserController::class, 'getAllOrders']);

Route::get('product/orders/confirm', [UserController::class, 'getConfirmOrders']);
Route::get('product/orders/pending', [UserController::class, 'getPendingOrders']);
Route::get('product/orders/cancel', [UserController::class, 'getCancelOrders']);
Route::get('product/orders/shipped', [UserController::class, 'getShippedOrders']);

Route::get('post/liked',[UserController::class, 'likedPosts']);
// user name change
Route::post('name/change', [UserController::class, 'changeUserName']);


Route::post('refund', [UserController::class, 'refundPayment']);










