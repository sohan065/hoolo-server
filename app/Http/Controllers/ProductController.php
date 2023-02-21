<?php



namespace App\Http\Controllers;


use SSL;
use Bkash;
use Product;
use Validator;
use Illuminate\Http\Request;




class ProductController extends Controller

{
    // get product list by brand uuid
     public function getProductByBrand(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:brands,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return Product::getProductByBrand($validated);
    }
    // get product list by gcategory uuid
     public function getProductByGcategory(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:g_categories,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return Product::getProductByGcategory($validated);
    }
    //get all products

    public function getAllProduct(Request $request)

    {
        // return $request->all();

        return Product::getAllProduct();
    }

    public function getByPcategory($uuid)
    {
        return Product::getByPcategory($uuid);
    }

    //get product details

    public function getProductDetails(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:products,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return Product::productDetails($validated);
    }

    // product order by CARD
    public function productOrderByCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart' => 'bail|required|array|min:1',
            'address_uuid' => 'bail|required|exists:shipping_addresses,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $token = $request->header('token');
        $validated = $request->only(['cart', 'address_uuid']);
        return Product::productOrderByCard($validated, $token);
    }
    // product order CARD payment success 
    public function productCardSuccess(Request $request)
    {
        $credentials = $request->all();
        return SSL::productCardSuccess($credentials);
    }
    // product order CARD payment fail 
    public function productCardFail(Request $request)
    {
        $credentials = $request->all();
        return SSL::productCardFail($credentials);
    }
    // product order CARD payment cancel 
    public function productCardCancel(Request $request)
    {
        $credentials = $request->all();
        return SSL::productCardCancel($credentials);
    }
    // product order CARD payment refund 
    public function productCardRefund(Request $request)
    {
        $credentials = $request->all();
        return SSL::productCardRefund($credentials);
    }

    // product order by cod
    public function productOrderByCod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart' => 'bail|required|array|min:1',
            'address_uuid' => 'bail|required|exists:shipping_addresses,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $token = $request->header('token');
        $validated = $request->only(['cart', 'address_uuid']);
        return Product::productOrderByCod($validated, $token);
    }
    // product order by bkash
    public function productOrderByBkash(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart' => 'bail|required|array|min:1',
            'address_uuid' => 'bail|required|exists:shipping_addresses,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $token = $request->header('token');
        $validated = $request->only(['cart', 'address_uuid']);
        return Product::productOrderByBkash($validated, $token);
    }
    //product order execute by Bkash
    public function orderExecute($paymentId)
    {
        return Bkash::productPaymentExecute($paymentId);
    }
    // product order cancel by bkash
    public function orderCancel($paymentId)
    {
        return Bkash::productPaymentCancel($paymentId);
    }

    // store new product

    public function saveProduct(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'category_uuid' => 'bail|required|string|min:10|exists:categories,uuid',

            'brand_uuid' => 'bail|required|string|min:10|exists:brands,uuid',

            'name' => 'bail|required|string|min:3',

            'price' => 'bail|required|integer',

            'stock' => 'bail|required|integer',

            'tags' => 'bail|nullable|string',

            'details' => 'bail|required|string|min:20',

            'cover_uuid' => 'bail|required|string|exists:product_galleries,uuid',

            'images_uuid' => 'bail|required|array|min:1',

            'discount' => 'bail|nullable|numeric',

            'discount_type' => 'bail|nullable|numeric',

            'discount_duration' => 'bail|nullable|string',

            'variant' => 'bail|nullable|boolean',

            // 'variant_stock' => 'bail|required|array|min:1',

            'attributes_uuid' => 'bail|nullable|string|min:10|exists:attributes,uuid',

            'attributes_values' => 'bail|nullable|array|min:1',

            'attributes_prices' => 'bail|nullable|array|min:1',

            'attributes_images_uuid' => 'bail|nullable|array|min:1',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $token = $request->header('token');

        $validated = $request->only([

            'category_uuid', 'brand_uuid', 'attributes_uuid', 'name',

            'price', 'discount', 'details', 'cover_uuid', 'images_uuid', 'tags', 'discount_type', 'discount_duration',

            'variant', 'stock', 'attributes_values', 'attributes_prices', 'attributes_images_uuid'

        ]);

        return Product::saveProduct($validated, $token);
    }

    // featured product get all

    public function getAllFeaturedProduct()

    {

        return Product::getAllFeaturedProduct();
    }

    // featured product store

    public function storeFeatured(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'product_uuid' => 'bail|required|string|unique:featured_products,product_uuid|exists:products,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['product_uuid']);

        return Product::storeFeatured($validated);
    }

    // featured product delete

    public function deleteFeatured(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|min:3|exists:featured_products,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return Product::deleteFeatured($validated);
    }
     // get all grand category campain product list by grand campaign uuid
    public function getAllGrandCampaignProduct(Request $request)
    {   
        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:g_category_campaigns,uuid',
        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return Product::getAllGrandCampaignProduct( $validated);
    }
     // get all child  campain product list by child campaign uuid
    public function getAllChildCampaignProduct(Request $request)
    {   
        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:category_campaigns,uuid',
        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return Product::getAllChildCampaignProduct( $validated);
    }
    public function getAllCampaignProduct()

    {

        return Product::getAllCampaignProduct();
    }

    // campaign product store

    public function storeCampaign(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'product_uuid' => 'bail|required|array|min:1',

            'title' => 'bail|required|string|min:3',

            'cover' => 'bail|required|image|mimes:jpg,jpeg,png',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $cover = $request->file('cover');

        $validated = $request->only(['product_uuid', 'title']);

        return Product::storeCampaign($validated, $cover);
    }

    // campaign product delete

    public function deleteCampaign(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|min:3|exists:product_campaigns,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return Product::deleteCampaign($validated);
    }
}
