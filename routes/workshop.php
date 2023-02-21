<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\WorkShopController;
use Illuminate\Support\Facades\Route;

//prefix workshop
//get all workshop by instructor uuid
Route::get('instructor', [WorkShopController::class, 'getWorkshopByInstructor']);
Route::get('get/all', [WorkShopController::class, 'getAllWorkshop']);

Route::post('store', [WorkShopController::class, 'saveWorkshop']);

Route::post('edit', [WorkShopController::class, 'editCourse']);

Route::post('update', [WorkShopController::class, 'updateCourse']);

Route::post('delete', [WorkShopController::class, 'deleteCourse']);

// get all workshop list and details
Route::get('get/details', [WorkShopController::class, 'getWorkshopDetails']);

//prefix workshop
Route::group(['prefix' => 'gallery', 'as' => 'gallery'], function () {

    Route::post('create', [WorkShopController::class, 'createWorkshopGallery']);

    Route::post('delete', [WorkShopController::class, 'deleteWorkshopGallery']);
});

//prefix workshop

Route::group(['prefix' => 'featured', 'as' => 'featured'], function () {

    Route::post('store', [WorkShopController::class, 'storeFeaturedWorkshop']);

    Route::post('delete', [WorkShopController::class, 'deleteFeaturedWorkshop']);

    Route::get('get/all', [WorkShopController::class, 'getAllFeaturedWorkshop']);
});

// workshop payment 
Route::group(['prefix' => 'payment', 'as' => 'payment'], function () {
    Route::post('booking', [WorkShopController::class, 'bookingWorkshop']); #free course
    Route::post('bkash', [WorkShopController::class, 'orderCourseByBkash']);
    Route::get('execute/{paymentId}', [WorkShopController::class, 'orderExecute']);
    Route::get('cancel/{paymentId}', [WorkShopController::class, 'orderCancel']);
    // CARD payment / SSL
    Route::post('card', [WorkShopController::class, 'orderWorkshopByCard']);
});
