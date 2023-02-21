<?php



use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BrandController;



// Prefix is brand 

Route::post('store', [BrandController::class, 'store']);

Route::post('edit', [BrandController::class, 'edit']);

Route::post('update', [BrandController::class, 'update']);

Route::post('delete', [BrandController::class, 'delete']);

Route::get('get/all', [BrandController::class, 'getAllBrand']);



Route::post('test', [BrandController::class, 'test']);



// Prefix is brand 

Route::group(['prefix' => 'featured', 'as' => 'featured'], function () {

    Route::post('store', [BrandController::class, 'storeFeatured']);

    Route::post('delete', [BrandController::class, 'deleteFeatured']);

    Route::get('get/all', [BrandController::class, 'getAllFeaturedBrand']);
});

// Prefix is brand 
Route::group(['prefix' => 'campaign', 'as' => 'campaign'], function () {

    Route::post('store', [BrandController::class, 'storeCampaign']);

    Route::post('delete', [BrandController::class, 'deleteCampaign']);

    Route::get('get/all', [BrandController::class, 'getAllCampaignBrand']);
    Route::get('products', [BrandController::class, 'getAllCampaignBrandProduct']);
});
