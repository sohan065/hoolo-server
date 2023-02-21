<?php

namespace App\Http\Controllers;

use Category;
use Validator;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function  getAllGcategory()
    {
        return Category::getAllGcategory();
    }
    function storeGcategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3|unique:g_categories,name',
            'icon' => 'bail|nullable|image',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $icon = $request->file('icon');
        $validated = $request->only(['name']);
        return Category::storeGcategory($validated, $icon);
    }

    function editGcategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:g_categories,uuid',

        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return Category::editGcategory($validated);
    }
    function updateGcategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3|max:16',
            'uuid' => 'bail|required|string|exists:g_categories,uuid',
            'icon' => 'bail|nullable|image',

        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $icon = $request->file('icon');
        $validated = $request->only(['name', 'uuid']);

        return Category::updateGcategory($validated, $icon);
    }
    function deleteGcategory(Request $request)
    {
        //to do
    }

    function  getAllPcategory()
    {
        return Category::getAllPcategory();
    }
    function storePcategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3|unique:p_categories,name',
            'gcategory_uuid' => 'bail|required|string|exists:g_categories,uuid',
            'icon' => 'bail|nullable|image',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $icon = $request->file('icon');
        $validated = $request->only(['name', 'gcategory_uuid']);
        return Category::storePcategory($validated, $icon);
    }

    function editPcategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:p_categories,uuid',

        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return Category::editPcategory($validated);
    }
    function updatePcategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3',
            'uuid' => 'bail|required|string|exists:p_categories,uuid',
            'icon' => 'bail|nullable|image',

        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $icon = $request->file('icon');
        $validated = $request->only(['name', 'uuid']);

        return Category::updatePcategory($validated, $icon);
    }
    function deletePcategory(Request $request)
    {
        //to do
    }

    function  getAllCategory()
    {
        return Category::getAllCategory();
    }
    function storeCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3|unique:categories,name',
            'pcategory_uuid' => 'bail|required|string|exists:p_categories,uuid',
            'icon' => 'bail|nullable|image',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $icon = $request->file('icon');
        $validated = $request->only(['name', 'pcategory_uuid']);
        return Category::storeCategory($validated, $icon);
    }

    function editCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:categories,uuid',

        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return Category::editCategory($validated);
    }

    function updateCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3',
            'uuid' => 'bail|required|string|exists:categories,uuid',
            'icon' => 'bail|nullable|image',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $icon = $request->file('icon');
        $validated = $request->only(['name', 'uuid']);
        return Category::updateCategory($validated, $icon);
    }

    function deleteCategory(Request $request)
    {
        //to do
    }

    function getAllDpCategory()
    {
        return Category::getAllDpCategory();
    }
    function storeDpCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3|unique:dp_categories,name',
            'icon' => 'bail|nullable|image|mimes:jpg,jpeg,png,webp',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $icon=$request->file('icon');
        $validated = $request->only(['name']);
        return Category::storeDpCategory($validated,$icon);
    }

    function editDpCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:dp_categories,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);

        return Category::editDpCategory($validated);
    }
    function updateDpCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3',
            'uuid' => 'bail|required|string|exists:dp_categories,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['name', 'uuid']);

        return Category::updateDpCategory($validated);
    }

    public function storeFeatured(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_uuid' => 'bail|required|string|unique:featured_categories,category_uuid|exists:categories,uuid',
            'cover' => 'bail|required|image|mimes:jpg,jpeg,png',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $cover = $request->File('cover');
        $validated = $request->only(['category_uuid']);
        return Category::storeFeatured($validated, $cover);
    }
    public function getAllFeaturedCategory()
    {
        return Category::getAllFeaturedCategory();
    }
    public function deleteFeatured(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|min:3|exists:featured_categories,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return Category::deleteFeatured($validated);
    }
    public function getAllCampaignCategory()
    {
        return Category::getAllCampaignCategory();
    }
    public function storeCampaign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_uuid' => 'bail|required|array|min:1',
            'title' => 'bail|required|string|min:3',
            'cover' => 'bail|required|image|mimes:jpg,jpeg,png',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $cover = $request->file('cover');
        $validated = $request->only(['category_uuid', 'title']);
        return Category::storeCampaign($validated, $cover);
    }
    // store grand campaign 
    public function storeGrandCampaign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|array|min:1',
            'title' => 'bail|required|string|min:3',
            'cover' => 'bail|required|image|mimes:jpg,jpeg,png',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $cover = $request->file('cover');
        $validated = $request->only(['uuid', 'title']);
        return Category::storeGrandCampaign($validated, $cover);
    }
    // get grand campaign
    public function getAllGrandCampaignCategory()
    {
        return Category::getAllGrandCampaignCategory();
    }
    public function deleteCampaign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|min:3|exists:category_campaigns,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return Category::deleteCampaign($validated);
    }
}
