<?php



use App\Http\Controllers\ProductController;

use App\Http\Controllers\ProductGalleryController;

use Illuminate\Support\Facades\Route;



//prefix product

Route::group(['prefix' => 'gallery', 'as' => 'gallery'], function () {

    Route::post('store', [ProductGalleryController::class, 'storeProductGallery']);

    Route::post('delete', [ProductGalleryController::class, 'deleteProductGallery']);

});

//prefix product

//prefix product
Route::group(['prefix' => 'order', 'as' => 'order'], function () {
    Route::post('cod', [ProductController::class, 'productOrderByCod']);
    Route::post('bkash', [ProductController::class, 'productOrderByBkash']);
    Route::get('bkash/execute/{paymentId}', [ProductController::class, 'orderExecute']);
    Route::get('bkash/cancel/{paymentId}', [ProductController::class, 'orderCancel']);
    Route::post('card', [ProductController::class, 'productOrderByCard']);
    Route::post('card/refund', [ProductController::class, 'productCardRefund']);
});

//prefix product

Route::post('store', [ProductController::class, 'saveProduct']);

Route::post('edit', [ProductController::class, 'editProduct']);

Route::post('update', [ProductController::class, 'updateProduct']);

Route::post('delete', [ProductController::class, 'deleteProduct']);

Route::get('get/all', [ProductController::class, 'getAllProduct']);

Route::post('get/details', [ProductController::class, 'getProductDetails']);

Route::get('pcategory/get/{uuid}',[ProductController::class,'getByPcategory']);


Route::get('brand',[ProductController::class,'getProductByBrand']);

Route::get('get/gcategory',[ProductController::class,'getProductByGcategory']);

//prefix product

Route::group(['prefix' => 'featured', 'as' => 'featured'], function () {

    Route::post('store', [ProductController::class, 'storeFeatured']);

    Route::post('delete', [ProductController::class, 'deleteFeatured']);

    Route::get('get/all', [ProductController::class, 'getAllFeaturedProduct']);

});



//prefix product

Route::group(['prefix' => 'campaign', 'as' => 'campaign'], function () {

    Route::post('store', [ProductController::class, 'storeCampaign']);

    Route::post('delete', [ProductController::class, 'deleteCampaign']);

    Route::get('get/all', [ProductController::class, 'getAllCampaignProduct']);
    
    Route::get('get/grand', [ProductController::class, 'getAllGrandCampaignProduct']);
    Route::get('get/child', [ProductController::class, 'getAllChildCampaignProduct']);

});

