<?php

namespace App\Http\Controllers;

use Banner;
use Validator;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function getAllBanner()
    {
        return Banner::getAllBanner();
    }
    public function createBanner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'banner' => 'bail|required|image|mimes:jpg,jpeg,png,webp',
            'type' => 'bail|required|string',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $file = $request->file('banner');
        $validated = $request->only(['type']);
        return Banner::createBanner($validated, $file);
    }
    public function deleteBanner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|exists:banners,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return Banner::deleteBanner($validated);
    }
}
