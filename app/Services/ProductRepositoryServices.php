<?php



namespace App\Services;




use SSL;
use Bkash;
use Token;
use Invoice;

use Exception;

use FileSystem;

use App\Models\User;

use App\Models\Product;

use Illuminate\Support\Str;

use App\Models\ProductOrder;

use App\Models\ProductDetails;

use App\Models\ProductGallery;
use App\Models\GCategory;
use App\Models\GCategoryCampaign;
use App\Models\CategoryCampaign;
use App\Models\PCategory;

use App\Models\Category;

use App\Models\ProductVariant;

use App\Models\FeaturedProduct;

use App\Models\ProductCampaign;

use App\Models\ProductOrderDetail;

use App\Models\ProductOrderPayment;

use App\Models\ShippingAddress;

use App\Models\TempProductOrder;

use Illuminate\Support\Facades\Log;

use App\Repositories\ProductRepositoryInterface;



class ProductRepositoryServices implements ProductRepositoryInterface

{

    // product order by cod
    public function productOrderByCod($credentials, $token)
    {
        $tokenInfo = Token::decode($token);
        $userId = User::where('uuid', $tokenInfo['uuid'])->first()->id;
        $shippingAddress = ShippingAddress::where('uuid', $credentials['address_uuid'])->first();
        foreach ($credentials['cart'] as $key => $cart) {
            $productInfo = Product::where('uuid', $cart['uuid'])->with('merchant:uuid,id', 'details:product_uuid,price,stock')->first();
            if ($productInfo->details->stock < $cart['quantity']) {
                return response(['message' => 'out of stock'], 204);
            }
        }
        $orderCode = Invoice::create($userId, 'product');

        foreach ($credentials['cart'] as $key => $cart) {
            $product_uuid = $cart['uuid'];
            $quantity = $cart['quantity'];

            $productInfo = Product::where('uuid', $product_uuid)->with('merchant:uuid,id', 'details:product_uuid,price,stock')->first();
            try {
                ProductOrder::create([
                    'uuid' => Str::uuid(),
                    'user_uuid' => $tokenInfo['uuid'],
                    'product_uuid' => $product_uuid,
                    'merchant_uuid' => $productInfo->merchant->uuid,
                    'quantity' => $quantity,
                    'price' => $productInfo->details->price,
                    'order_code' =>  $orderCode,
                ]);
                $productInfo = ProductDetails::where('product_uuid', $product_uuid)->first();
                $remainStock = $productInfo['stock'] - $quantity;
                ProductDetails::where('product_uuid', $product_uuid)->update([
                    'stock' => $remainStock,
                ]);
            } catch (Exception $e) {
                log::error($e);
            }
        }

        try {
            $result = ProductOrderDetail::create([
                'uuid' => Str::uuid(),
                'phone' => $shippingAddress->phone,
                'name' => $shippingAddress->name,
                'user_uuid' => $tokenInfo['uuid'],
                'order_code' => $orderCode,
                'address' => $shippingAddress->address,
                'post_code' => $shippingAddress->post_code_uuid,
                'thana' => $shippingAddress->thana_uuid,
                'city' => $shippingAddress->city_uuid,
                'state' => $shippingAddress->state_uuid,
                'country' => $shippingAddress->country_uuid,
                'shipping_cost' => 0,
            ]);
        } catch (Exception $e) {
            log::error($e);
            $result = false;
        }
        if ($result) {
            try {
                $productPayment = ProductOrderPayment::create([
                    'uuid' => Str::uuid(),
                    'user_uuid' => $tokenInfo['uuid'],
                    'order_code' => $orderCode,
                    'payment_method' => 'COD',
                    'payment_with' => 'COD',
                ]);
            } catch (Exception $e) {
                log::error($e);
                $productPayment = false;
            }
            if ($productPayment) {

                return response(['message' => 'success', 'orderCode' => $orderCode], 201);
            }
        }
        $deleteProductOrder = ProductOrder::where('order_code', $orderCode)->delete();
        $deleteProductOrderDetails = ProductOrderDetail::where('order_code', $orderCode)->delete();
        return response(['message' => 'not acceptable'], 406);
    }
    // product order by bkash
    public function productOrderByBkash($credentials, $token)
    {
        $tokenInfo = Token::decode($token);
        $userId = User::where('uuid', $tokenInfo['uuid'])->first()->id;
        $orderCode = Invoice::create($userId, 'product');
        $totalPrice = 0;
        foreach ($credentials['cart'] as $key => $cart) {
            $productInfo = ProductDetails::where('product_uuid', $cart['uuid'])->first();
            if ($productInfo->stock < $cart['quantity']) {
                return response(['message' => 'out of stock'], 204);
            }
        }
        foreach ($credentials['cart'] as $key => $cart) {
            $product_uuid = $cart['uuid'];
            $quantity = $cart['quantity'];

            $productInfo = Product::where('uuid', $product_uuid)->with('merchant:uuid,id', 'details:product_uuid,price,stock')->first();
            try {
                TempProductOrder::create([
                    'uuid' => Str::uuid(),
                    'user_uuid' => $tokenInfo['uuid'],
                    'product_uuid' => $product_uuid,
                    'merchant_uuid' => $productInfo->merchant->uuid,
                    'address_uuid' => $credentials['address_uuid'],
                    'quantity' => $quantity,
                    'price' => $productInfo->details->price,
                    'payment_method' => 'Bkash',
                    'order_code' =>  $orderCode,
                ]);
            } catch (Exception $e) {
                log::error($e);
            }
            $totalPrice += $productInfo->details->price * $quantity;
        }
        return Bkash::productPaymentCreate($totalPrice, $orderCode);
    }
    // product order by CARD
    public function productOrderByCard($credentials, $token)
    {
        $tokenInfo = Token::decode($token);
        $userId = User::where('uuid', $tokenInfo['uuid'])->first()->id;

        foreach ($credentials['cart'] as $key => $cart) {
            $productInfo = ProductDetails::where('product_uuid', $cart['uuid'])->first();
            if ($productInfo->stock < $cart['quantity']) {
                return response(['message' => 'out of stock'], 204);
            }
        }
        $orderCode = Invoice::create($userId, 'product');
        $totalPrice = 0;
        foreach ($credentials['cart'] as $key => $cart) {
            $product_uuid = $cart['uuid'];
            $quantity = $cart['quantity'];
            $productInfo = Product::where('uuid', $product_uuid)->with('merchant:uuid,id', 'details:product_uuid,price,stock')->first();
            try {
                TempProductOrder::create([
                    'uuid' => Str::uuid(),
                    'user_uuid' => $tokenInfo['uuid'],
                    'merchant_uuid' => $productInfo->merchant->uuid,
                    'product_uuid' => $product_uuid,
                    'order_code' =>  $orderCode,
                    'quantity' => $quantity,
                    'price' => $productInfo->details->price,
                    'payment_method' => 'CARD',
                    'address_uuid' => $credentials['address_uuid'],
                ]);
            } catch (Exception $e) {
                log::error($e);
            }
            $totalPrice += $productInfo->details->price * $quantity;
        }
        return SSL::productOrderByCard($credentials, $totalPrice, $orderCode);
    }
    public function getAllProduct()

    {

        return Product::select('name', 'uuid')->with('details:product_uuid,price,cover,stock,discount,discount_type,discount_duration', 'details.cover')->orderBy('id', 'DESC')->paginate(30);
    }
   // get product list by brand uuid
    public function getProductByBrand($uuid)
    {
        return Product::whereIn('brand_uuid', $uuid)->select('name', 'uuid')->with('details:product_uuid,price,cover,stock,discount,discount_type,discount_duration', 'details.cover')->orderBy('id', 'DESC')->paginate(30);
    }
   // get product list by gcategory uuid
    public function getProductByGcategory($uuid)
    {
        $pcategory= PCategory::where('gcategory_uuid',$uuid)->pluck('uuid');
        $category= Category::whereIn('pcategory_uuid', $pcategory)->pluck('uuid');
        
        return Product::whereIn('category_uuid',  $category)->select('name', 'uuid')->with('details:product_uuid,price,cover,stock,discount,discount_type,discount_duration', 'details.cover')->orderBy('id', 'DESC')->paginate(30);
    }
    public function getByPcategory($uuid)
    {
        $categories = Category::where('pcategory_uuid', $uuid)->pluck('uuid');
        return Product::whereIn('category_uuid', $categories)->select('name', 'uuid')->with('details:product_uuid,price,cover,stock,discount,discount_type,discount_duration', 'details.cover')->orderBy('id', 'DESC')->paginate(30);
    }

    public function productDetails($credentials)
    {

        $pcategory = Product::where('uuid', $credentials['uuid'])->with('category.pcategory')->first()->category->pcategory->uuid;

        $categories = Category::where('pcategory_uuid', $pcategory)->pluck('uuid');

        $recommended = Product::whereIn('category_uuid', $categories)->where('uuid', '!=', $credentials['uuid'])->select('name', 'uuid')->with('details:product_uuid,price,cover,stock,discount,discount_type,discount_duration', 'details.cover')->take(12)->get();

        $productDetails = Product::where('uuid', $credentials['uuid'])->with('merchant', 'brand', 'category.pcategory.gcategory', 'details.gallery', 'details.cover', 'variant.attribute')->first();

        return response(['productDetails' => $productDetails, 'recommended' => $recommended], 200);
    }

    public function saveProduct($credentials, $token)

    {

        $tokenInfo = Token::decode($token);

        $merchant_uuid = $tokenInfo['uuid'];



        if ($credentials['variant']) {

            $attributeImages = array();

            if (count($credentials['attributes_values']) > count($credentials['attributes_images_uuid'])) {

                for ($x = count($credentials['attributes_images_uuid']); $x < count($credentials['attributes_values']); $x++) {

                    $credentials['attributes_images_uuid'][$x] = null;
                }
            }

            foreach ($credentials['attributes_images_uuid'] as $image_uuid) {

                if (!$image_uuid == null) {

                    $exist = ProductGallery::where('uuid', $image_uuid)->first();

                    if (!$exist) {

                        $response = ['attributes_images_uuid' => array('selected image is invalid')];

                        return response($response, 422);
                    }

                    array_push($attributeImages, $exist->id);
                } else {

                    array_push($attributeImages, null);
                }
            }
        }



        $productImages = array();

        foreach ($credentials['images_uuid'] as $image_uuid) {

            $exist = ProductGallery::where('uuid', $image_uuid)->first();

            if (!$exist) {

                $response = ['images_uuid' => array('selected image is invalid')];

                return response($response, 422);
            }

            array_push($productImages, $exist->id);
        }



        if (array_key_exists('discount', $credentials) && $credentials['discount'] != null) {

            if ($credentials['discount_type'] == null) {

                return response(['discount_type' => array('invalid discount type')], 422);
            }

            if ($credentials['discount_duration'] == null) {

                return response(['discount_duration' => array('invalid discount duration')], 422);
            }
        } else {

            $credentials['discount_type'] = null;

            $credentials['discount_duration'] = null;
        }



        $product = $this->storeProduct($credentials, $merchant_uuid);

        if ($product) {

            $details = $this->storeDetails($credentials, $productImages, $product->uuid);

            if ($details) {

                if (array_key_exists('variant', $credentials) && $credentials['variant'] == '1') {

                    if (count($credentials['attributes_values']) > count($credentials['attributes_prices'])) {

                        for ($x = count($credentials['attributes_prices']); $x < count($credentials['attributes_values']); $x++) {

                            $credentials['attributes_prices'][$x] = null;
                        }
                    }

                    if (count($credentials['attributes_values']) > count($credentials['attributes_images_uuid'])) {

                        for ($x = count($credentials['attributes_images_uuid']); $x < count($credentials['attributes_values']); $x++) {

                            $credentials['attributes_images_uuid'][$x] = null;
                        }
                    }

                    $variants = $this->storeVariants($credentials, $attributeImages, $product->uuid);

                    if ($variants) {

                        return response(['message' => 'success'], 201);
                    }

                    $deleteP = Product::where('uuid', $product->uuid)->delete();

                    $deletePD = ProductDetails::where('product_uuid', $product->uuid)->delete();
                }

                return response(['message' => 'success'], 201);
            }

            $deleteP = Product::where('uuid', $product->uuid)->delete();

            return response(['message' => 'not acceptable'], 406);
        }

        return response(['message' => 'not acceptable'], 406);
    }

    public function storeProduct($credentials, $merchatant_uuid)

    {

        $uuid = Str::uuid();

        $slug = strtolower($credentials['name']);

        $slug = trim($slug);

        $slug = preg_replace('/[^A-Za-z0-9 ]/', '', $slug);

        $slug = preg_replace('/\s+/', '-', $slug) . '-' . $uuid;

        try {

            $result = Product::create([

                'uuid' => $uuid,

                'merchant_uuid' => $merchatant_uuid,

                'category_uuid' => $credentials['category_uuid'],

                'brand_uuid' => $credentials['brand_uuid'],

                'name' => $credentials['name'],

                'slug' => $slug,

            ]);
        } catch (Exception $e) {

            Log::error($e);

            $result = false;
        }

        return $result;
    }

    public function storeDetails($credentials, $productImages, $product_uuid)

    {

        try {

            $details = trim($credentials['details']);

            $details = preg_replace('/\s+/', ' ', $details);

            $result = ProductDetails::create([

                'product_uuid' => $product_uuid,

                'stock' => $credentials['stock'],

                'tags' => $credentials['tags'],

                'price' => $credentials['price'],

                'details' => $details,

                'cover' => $credentials['cover_uuid'],

                'images' => $productImages,

                'discount' => $credentials['discount'],

                'discount_type' => $credentials['discount_type'],

                'discount_duration' => $credentials['discount_duration'],

            ]);
        } catch (Exception $e) {

            Log::error($e);

            $result = false;
        }

        return $result;
    }

    public function storeVariants($credentials, $attributeImages, $product_uuid)

    {

        try {

            $result = ProductVariant::create([

                'product_uuid' => $product_uuid,

                'attributes_uuid' => $credentials['attributes_uuid'],

                // 'variant_stock' => $credentials['variant_stock'],

                'values' => $credentials['attributes_values'],

                'images' => $attributeImages,

                'prices' => $credentials['attributes_prices'],

            ]);
        } catch (Exception $e) {

            Log::error($e);

            $result = false;
        }

        return $result;
    }

    public function editProduct()

    {

        //to do

    }

    public function updateProduct()

    {

        //to do

    }

    public function deleteProduct()

    {

        //to do

    }

    public function storeProductGallery($image, $token)

    {

        $path = FileSystem::storeFile($image, 'product/gallery');

        if ($path) {

            try {

                $uuid = Str::uuid();

                $tokenInfo = Token::decode($token);

                $merchant_uuid = $tokenInfo['uuid'];

                $result = ProductGallery::create([

                    'uuid' => $uuid,

                    'merchant_uuid' => $merchant_uuid,

                    'path' => $path,

                ]);
            } catch (Exception $e) {

                Log::error($e);

                $result = false;
            }

            if ($result) {

                return response($result, 201);
            }

            return response(['message' => 'not accepted'], 406);
        }
    }

    public function deleteProductGallery($uuid, $token)

    {

        $tokenInfo = Token::decode($token);

        $merchant_uuid = $tokenInfo['uuid'];

        $exists = ProductGallery::where('uuid', $uuid)->where('merchant_uuid', $merchant_uuid)->first();

        $path = $exists['path'];

        if ($exists) {

            try {

                FileSystem::deleteFile($path);

                $result = ProductGallery::where('uuid', $uuid)->where('merchant_uuid', $merchant_uuid)->delete();
            } catch (Exception $e) {

                Log::error($e);

                $result = false;
            }

            if ($result) {

                return response(['message' => 'success'], 200);
            }

            return response(['message' => 'not excepted'], 406);
        }
    }

    // get all featured product

    public function getAllFeaturedProduct()

    {

        return FeaturedProduct::with('product:uuid,name', 'product.details:product_uuid,price,cover', 'product.details.cover')->orderBy('id', 'DESC')->get();
    }

    // store featured product 

    public function storeFeatured($credentils)

    {

        try {

            $result = FeaturedProduct::create([

                'uuid' => Str::uuid(),

                'product_uuid' => $credentils['product_uuid'],

            ]);
        } catch (Exception $e) {

            Log::error($e);

            $result = false;
        }

        if ($result) {

            return response(['message' => 'created'], 201);
        }

        return response(['message' => 'not acceptable'], 406);
    }

    // delete featured product 

    public  function deleteFeatured($credentils)

    {

        try {

            $result = FeaturedProduct::where('uuid', $credentils['uuid'])->delete();
        } catch (Exception $e) {

            log::error($e);

            $result = false;
        }

        if ($result) {

            return response(['message' => 'deleted'], 410);
        }

        return response(['message' => 'not acceptable'], 406);
    }

    //get all campaign product

    public function getAllCampaignProduct()

    {

        return ProductCampaign::orderBy('id', 'DESC')->get();
    }

    // store campaign product 

    public function storeCampaign($credentils, $cover)

    {

        $array = array();

        foreach ($credentils['product_uuid'] as $categoryUuid) {

            $exist = Product::where('uuid', $categoryUuid)->first();

            if (!$exist) {

                return response(['message' => 'uuid not found'], 404);
            }

            array_push($array, $categoryUuid);
        }

        $path = FileSystem::storeFile($cover, 'campaign/product/cover');

        if ($path) {

            try {

                $result = ProductCampaign::create([

                    'uuid' => Str::uuid(),

                    'product_uuid' => json_encode($array),

                    'title' => $credentils['title'],

                    'cover' => $path,

                ]);
            } catch (Exception $e) {

                Log::error($e);

                $result = false;
            }

            if ($result) {

                return response(['message' => 'created'], 201);
            }

            $deleteFile = FileSystem::deleteFile($path);
        }

        return response(['message' => 'not acceptable'], 406);
    }

    // delete campaign product

    public  function deleteCampaign($credentils)

    {

        try {

            $result = ProductCampaign::where('uuid', $credentils['uuid'])->delete();
        } catch (Exception $e) {

            log::error($e);

            $result = false;
        }

        if ($result) {

            return response(['message' => 'deleted'], 410);
        }

        return response(['message' => 'not acceptable'], 406);
    }
    // get all grand category campain product list by grand campaign uuid
     public function getAllGrandCampaignProduct($credentials)
    {
        $exists = GCategoryCampaign::where('uuid', $credentials['uuid'])->first();
        return Product::whereIn('category_uuid', json_decode($exists->g_category_uuid))->select('name', 'uuid')->with('details:product_uuid,price,cover,stock,discount,discount_type,discount_duration', 'details.cover')->orderBy('id', 'DESC')->paginate(30);
    }
    // get all child  campain product list by child campaign uuid
     public function getAllChildCampaignProduct($credentials)
    {
        $exists = CategoryCampaign::where('uuid', $credentials['uuid'])->first();
        return Product::whereIn('category_uuid', json_decode($exists->category_uuid))->select('name', 'uuid')->with('details:product_uuid,price,cover,stock,discount,discount_type,discount_duration', 'details.cover')->orderBy('id', 'DESC')->paginate(30);
    }

}
