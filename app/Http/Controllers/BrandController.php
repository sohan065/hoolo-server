<?php



namespace App\Http\Controllers;



use App\Http\Requests\StoreProductValidation;

use Brand;

use Validator;

use Illuminate\Http\Request;



class BrandController extends Controller

{

    public  function getAllBrand()

    {

        return Brand::getAllBrand();
    }

    public  function store(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'name' => 'bail|required|string|min:3|max:50|unique:brands,name',

            'icon' => 'bail|required|image',

        ]);



        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }



        $icon = $request->file('icon');

        $validated = $request->only(['name']);



        return Brand::store($validated, $icon);
    }



    public  function edit(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|exists:brands,uuid',

        ]);



        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);



        return Brand::edit($validated);
    }



    public  function update(Request $request)

    {



        $validator = Validator::make($request->all(), [

            'uuid' => 'required|exists:brands,uuid',

            'name' => 'bail|required|min:3|max:50',

            'icon' => 'bail|required|image',

        ]);



        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }



        $icon = $request->file('icon');

        $validated = $request->only(['name', 'uuid']);

        return Brand::update($validated, $icon);
    }



    public  function delete(Request $request)

    {



        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|exists:brands,uuid',

        ]);



        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }



        $validated = $request->only(['uuid']);

        return Brand::delete($validated);
    }




    // featured brand store

    public  function storeFeatured(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'brand_uuid' => 'bail|required|string|unique:featured_brands,brand_uuid|exists:brands,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['brand_uuid']);

        return Brand::storeFeatured($validated);
    }

    public function getAllFeaturedBrand()

    {

        return Brand::getAllFeaturedBrand();
    }

    // featured brand delete

    public function deleteFeatured(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|min:3|exists:featured_brands,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return Brand::deleteFeatured($validated);
    }

    public function getAllCampaignBrand()

    {
        return Brand::getAllCampaignBrand();
    }
    // campaign product list by brand campaign uuid
    public function getAllCampaignBrandProduct(Request $request)

    {
        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:brand_campaigns,uuid',
        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);

        return Brand::getAllCampaignBrandProduct($validated);
    }

    public function storeCampaign(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'brand_uuid' => 'bail|required|array|min:1',

            'title' => 'bail|required|string|min:3',

            'cover' => 'bail|required|image|mimes:jpg,jpeg,png',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $cover = $request->file('cover');

        $validated = $request->only(['brand_uuid', 'title']);

        return Brand::storeCampaign($validated, $cover);
    }

    public function deleteCampaign(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|min:3|exists:brand_campaigns,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return Brand::deleteCampaign($validated);
    }
}
