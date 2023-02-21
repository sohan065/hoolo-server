<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttributesController;
use App\Http\Controllers\MerchantauthController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\RoleController;



// prefix admin
Route::post('login', [AdminController::class, 'adminLogin']);
Route::get('merchants', [MerchantauthController::class, 'getMerchantList']);
Route::post('attributes', [AttributesController::class, 'store']);

Route::group(['prefix' => 'roles', 'as' => 'roles'], function () {
    Route::post('store', [RoleController::class, 'createRole']);
    Route::post('edit', [RoleController::class, 'editRole']);
    Route::post('update', [RoleController::class, 'updateRole']);
    Route::post('delete', [RoleController::class, 'deleteRole']);
});
Route::group(['prefix' => 'module', 'as' => 'module'], function () {
    Route::post('store', [ModuleController::class, 'createModule']);
    Route::post('edit', [ModuleController::class, 'editModule']);
    Route::post('update', [ModuleController::class, 'updateModule']);
    Route::post('delete', [ModuleController::class, 'deleteModule']);
});
Route::group(['prefix' => 'instructor', 'as' => 'instructor'], function () {
    Route::get('all/info', [AdminController::class, 'getAllInstructor']);
});

Route::group(['prefix' => 'merchant', 'as' => 'merchant'], function () {
    Route::get('all/info', [AdminController::class, 'getAllMerchant']);
    Route::post('login', [AdminController::class, 'merchantLogin']);
    Route::post('update', [AdminController::class, 'merchantUpdate']);
});
Route::group(['prefix' => 'brand', 'as' => 'brand'], function () {
    Route::get('get/all', [AdminController::class, 'getAllBrand']);
    Route::post('update', [AdminController::class, 'updateBrand']);
});

Route::get('products', [AdminController::class, 'getProductList']);

Route::group(['prefix' => 'category', 'as' => 'category'], function () {
    Route::get('grand', [AdminController::class, 'getAllGcategory']);
    Route::get('parent', [AdminController::class, 'getAllPcategory']);
    Route::get('child', [AdminController::class, 'getAllCategory']);
    Route::get('dpcategory', [AdminController::class, 'getAllDpCategory']);
    
    Route::post('child/active', [AdminController::class, 'childCategoryStatus']);
    Route::post('grand/active', [AdminController::class, 'grandCategoryStatus']);
    Route::post('parent/active', [AdminController::class, 'parentCategoryStatus']);
    Route::post('dpcategory/active', [AdminController::class, 'dpcategoryCategoryStatus']);
    Route::group(['prefix' => 'update', 'as' => 'update'], function () {
        Route::post('grand', [AdminController::class, 'updateGcategory']);
        Route::post('parent', [AdminController::class, 'updatePcategory']);
        Route::post('child', [AdminController::class, 'updateCategory']);
        Route::post('dpcategory', [AdminController::class, 'updateDpCategory']);
    });
});
 
Route::get('product/orders', [AdminController::class, 'getAllProductOrder']);
Route::get('product/orders/confirm', [AdminController::class, 'getConfirmOrders']);
Route::get('product/orders/pending', [AdminController::class, 'getPendingOrders']);
Route::get('product/orders/cancel', [AdminController::class, 'getCancelOrders']);
Route::get('product/orders/shipped', [AdminController::class, 'getShippedOrders']);

Route::get('course/order/list', [AdminController::class, 'getAllCourseOrderList']);



