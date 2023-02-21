<?php

use App\Http\Controllers\AddressController;
use Illuminate\Support\Facades\Route;

// prefix address 
Route::group(['prefix' => 'shipping', 'as' => 'shipping'], function () {
    Route::get('get', [AddressController::class, 'allShippingAddress']);
    Route::get('user', [AddressController::class, 'getAllUserShippingAddress']);
    Route::post('store', [AddressController::class, 'storeShippingAddress']);
    Route::post('edit', [AddressController::class, 'editShippingAddress']);
    Route::post('update', [AddressController::class, 'updateShippingAddress']);
    Route::post('delete', [AddressController::class, 'deleteShippingAddress']);
});

Route::group(['prefix' => 'country', 'as' => 'country'], function () {
    Route::get('get/all', [AddressController::class, 'getAllCountry']);
    Route::post('store', [AddressController::class, 'storeCountry']);
    Route::post('edit', [AddressController::class, 'editCountry']);
    Route::post('update', [AddressController::class, 'updateCountry']);
    Route::post('delete', [AddressController::class, 'deleteCountry']);
});

Route::group(['prefix' => 'state', 'as' => 'state'], function () {
    Route::get('get/all', [AddressController::class, 'getAllState']);
    Route::post('info', [AddressController::class, 'getStateInfo']);
    Route::post('store', [AddressController::class, 'storeState']);
    Route::post('edit', [AddressController::class, 'editState']);
    Route::post('update', [AddressController::class, 'updateState']);
    Route::post('delete', [AddressController::class, 'deleteState']);
});

Route::group(['prefix' => 'city', 'as' => 'city'], function () {
    Route::get('get/all', [AddressController::class, 'getAllCity']);
    Route::post('info', [AddressController::class, 'getCityInfo']);
    Route::post('store', [AddressController::class, 'storeCity']);
    Route::post('edit', [AddressController::class, 'editCity']);
    Route::post('update', [AddressController::class, 'updateCity']);
    Route::post('delete', [AddressController::class, 'deleteCity']);
});

Route::group(['prefix' => 'thana', 'as' => 'thana'], function () {
    Route::get('get/all', [AddressController::class, 'getAllThana']);
    Route::post('info', [AddressController::class, 'getThanaInfo']);
    Route::post('store', [AddressController::class, 'storeThana']);
    Route::post('edit', [AddressController::class, 'editThana']);
    Route::post('update', [AddressController::class, 'updateThana']);
    Route::post('delete', [AddressController::class, 'deleteThana']);
});

Route::group(['prefix' => 'postcode', 'as' => 'postcode'], function () {
    Route::get('get/all', [AddressController::class, 'getAllPostcode']);
    Route::post('info', [AddressController::class, 'getAllPostcodeInfo']);
    Route::post('info/thana', [AddressController::class, 'getAllPostcodeInfoByThana']);
    Route::post('store', [AddressController::class, 'storePostcode']);
    Route::post('edit', [AddressController::class, 'editPostcode']);
    Route::post('update', [AddressController::class, 'updatePostcode']);
    Route::post('delete', [AddressController::class, 'deletePostcode']);
});
