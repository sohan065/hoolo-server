<?php

namespace App\Http\Controllers;

use Merchant;
use Validator;
use Illuminate\Http\Request;

class MerchantController extends Controller

{
    public function getMerchantInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:merchants,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return Merchant::getMerchantInfo($validated['uuid']);
    }

    public function getAllPosts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:merchants,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return Merchant::getAllPosts($validated['uuid']);
    }

    public function getAllProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:merchants,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return Merchant::getAllProducts($validated['uuid']);
    }
 // get all products of merchant by token
    public function getAllProductsByToken(Request $request)
    {
        $token = $request->header('token');

        return Merchant::getAllProductsByToken($token);
    }
 // get all products gallery of merchant by token
    public function getAllProductsGalleryByToken(Request $request)
    {
        $token = $request->header('token');

        return Merchant::getAllProductsGalleryByToken($token);
    }
    public function profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'bail|required|image'
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $token = $request->header('token');
        $file = $request->file('image');
        return Merchant::profile($token, $file);
    }
    public function editMerchantInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|exits:merchants,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return Merchant::editMerchantInfo($validated);
    }
}
