<?php

namespace App\Http\Controllers;

use Product;
use Validator;
use Illuminate\Http\Request;

class ProductGalleryController extends Controller
{
    function storeProductGallery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'bail|required|image|mimes:jpeg,jpg,png,webp',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $image = $request->file('image');
        $token = $request->header('token');
        return  Product::storeProductGallery($image, $token);
    }
    function deleteProductGallery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|exists:product_galleries,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $token = $request->header('token');
        $validated = $request->only(['uuid']);

        return  Product::deleteProductGallery($validated, $token);
    }
}
