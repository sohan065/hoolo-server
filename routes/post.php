<?php



use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;

use App\Http\Controllers\PostGalleryController;





// prefix post



Route::group(['prefix' => 'gallery', 'as' => 'gallery'], function () {

    Route::post('create', [PostGalleryController::class, 'createPostGallery']);

    Route::post('delete', [PostGalleryController::class, 'deletePostGallery']);
});



Route::post('store', [PostController::class, 'createPost']);

Route::post('delete', [PostController::class, 'deletePost']);

Route::get('get', [PostController::class, 'getPost']);

Route::get('get/all', [PostController::class, 'getAllPost']);

Route::post('view', [PostController::class, 'increaseView']);

// increase post share

Route::post('share', [PostController::class, 'increaseShare']);



Route::post('like/store', [PostController::class, 'storePostLike']);

Route::post('comment', [PostController::class, 'storePostComment']);





Route::post('comment/update', [PostController::class, 'updatePostComment']);

Route::post('comment/delete', [PostController::class, 'deletePostComment']);
