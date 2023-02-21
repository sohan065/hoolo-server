<?php



use App\Http\Controllers\UserController;

use App\Http\Controllers\InstructorController;

use Illuminate\Support\Facades\Route;



//prefix instructor 

Route::post('registration', [InstructorController::class, 'instructorReg']);

Route::post('resend-otp', [InstructorController::class, 'resendOtpCode']);

Route::post('otp-verification', [InstructorController::class, 'otpVerify']);

Route::post('info', [InstructorController::class, 'createInstructorInfo']);

Route::post('detail', [InstructorController::class, 'createInstructorDetail']);

Route::get('get/all', [InstructorController::class, 'getAllInstructor']);
Route::post('information',[InstructorController::class,'getInstructorByUuid']);


//prefix instructor 

Route::group(['prefix' => 'featured', 'as' => 'featured'], function () {

    Route::post('store', [InstructorController::class, 'storeFeatured']);

    Route::post('delete', [InstructorController::class, 'deleteFeatured']);

    Route::get('get/all', [InstructorController::class, 'getAllFeaturedInstructor']);

});



Route::post('profile/store', [UserController::class, 'profileStore']);



Route::post('test', [InstructorController::class, 'test']);

