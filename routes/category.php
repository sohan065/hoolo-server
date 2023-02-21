<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// prefix category
Route::group(['prefix' => 'gCategory', 'as' => 'gCategory'], function () {
    Route::post('store', [CategoryController::class, 'storeGcategory']);
    Route::post('edit', [CategoryController::class, 'editGcategory']);
    Route::post('update', [CategoryController::class, 'updateGcategory']);
    Route::post('delete', [CategoryController::class, 'deleteGcategory']);
    Route::get('get/all', [CategoryController::class, 'getAllGcategory']);
});
// prefix category
Route::group(['prefix' => 'pCategory', 'as' => 'pCategory'], function () {
    Route::post('store', [CategoryController::class, 'storePcategory']);
    Route::post('edit', [CategoryController::class, 'editPcategory']);
    Route::post('update', [CategoryController::class, 'updatePcategory']);
    Route::post('delete', [CategoryController::class, 'deletePcategory']);
    Route::get('get/all', [CategoryController::class, 'getAllPcategory']);
});
// prefix category
Route::group(['prefix' => 'category', 'as' => 'category'], function () {
    Route::post('store', [CategoryController::class, 'storeCategory']);
    Route::post('edit', [CategoryController::class, 'editCategory']);
    Route::post('update', [CategoryController::class, 'updateCategory']);
    Route::post('delete', [CategoryController::class, 'deleteCategory']);
    Route::get('get/all', [CategoryController::class, 'getAllCategory']);
});
// prefix category
Route::group(['prefix' => 'DpCategory', 'as' => 'DpCategory'], function () {
    Route::post('store', [CategoryController::class, 'storeDpCategory']);
    Route::post('edit', [CategoryController::class, 'editDpCategory']);
    Route::post('update', [CategoryController::class, 'updateDpCategory']);
    Route::post('delete', [CategoryController::class, 'deleteDpCategory']);
    Route::get('get/all', [CategoryController::class, 'getAllDpCategory']);
});
// prefix category
Route::group(['prefix' => 'featured', 'as' => 'featured'], function () {
    Route::post('store', [CategoryController::class, 'storeFeatured']);
    Route::post('delete', [CategoryController::class, 'deleteFeatured']);
    Route::get('get/all', [CategoryController::class, 'getAllFeaturedCategory']);
});
// prefix category
Route::group(['prefix' => 'campaign', 'as' => 'campaign'], function () {
    Route::post('child/store', [CategoryController::class, 'storeCampaign']);
    Route::post('child/delete', [CategoryController::class, 'deleteCampaign']);
    Route::get('get/child', [CategoryController::class, 'getAllCampaignCategory']);
     Route::post('grand/store', [CategoryController::class, 'storeGrandCampaign']);
     Route::get('get/grand', [CategoryController::class, 'getAllGrandCampaignCategory']);
});
