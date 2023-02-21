<?php



use App\Http\Controllers\CourseController;

use Illuminate\Support\Facades\Route;



//prefix course
Route::post('instructor',[CourseController::class, 'getCourseByInstructor']);
// get all ordered course by user uuid
Route::get('user',[CourseController::class, 'getAllUserCourse']);
// get all course by dp category uuid
Route::get('get/dpcategory',[CourseController::class, 'getCourseByDpCategory']);
Route::get('get/all', [CourseController::class, 'getAllCourse']);

Route::post('store', [CourseController::class, 'saveCourse']);

Route::post('edit', [CourseController::class, 'editCourse']);

Route::post('update', [CourseController::class, 'updateCourse']);

Route::post('delete', [CourseController::class, 'deleteCourse']);

// get all course list and details
Route::post('get/details', [CourseController::class, 'getCourseDetails']);



//prefix course

Route::group(['prefix' => 'gallery', 'as' => 'gallery'], function () {

    Route::post('create', [CourseController::class, 'createCourseGallery']);

    Route::post('delete', [CourseController::class, 'deleteCourseGallery']);

});

//prefix course

Route::group(['prefix' => 'featured', 'as' => 'featured'], function () {

    Route::post('store', [CourseController::class, 'storeFeaturedCourse']);

    Route::post('delete', [CourseController::class, 'deleteFeaturedCourse']);

    Route::get('get/all', [CourseController::class, 'getAllFeaturedCourse']);

});

// course payment 
Route::group(['prefix' => 'payment', 'as' => 'payment'], function () {
    Route::post('booking', [CourseController::class, 'bookingCourse']); #free course
    Route::post('bkash', [CourseController::class, 'orderCourseByBkash']);
    Route::get('execute/{paymentId}', [CourseController::class, 'orderExecute']);
    Route::get('cancel/{paymentId}', [CourseController::class, 'orderCancel']);
    // CARD payment
    Route::post('card', [CourseController::class, 'orderCourseByCard']);
});


