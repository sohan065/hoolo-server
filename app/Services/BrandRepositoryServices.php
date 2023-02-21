<?php



namespace App\Services;



use Log;

use Exception;

use FileSystem;

use App\Models\Brand;
use App\Models\Product;

use App\Models\BrandCampaign;

use App\Models\FeaturedBrand;

use Illuminate\Support\Str;

use App\Repositories\BrandRepositoryInterface;



class BrandRepositoryServices implements BrandRepositoryInterface

{

    public function getAllBrand()
    {

        return Brand::orderBy('id', 'DESC')->get();
    }

    public function store($credentials, $icon)

    {

        $uuid = Str::uuid();

        $name = $credentials['name'];

        $icon = $icon;



        $exists = Brand::where('name', $name)->first();



        if ($exists) {

            return response(['message' => 'already exists'], 302);
        }

        if ($icon == null) {

            $result = Brand::create([

                'uuid' => $uuid,

                'name' => $name,

                'status' => 0,

                'requested' => 0,

            ]);

            return response(['message' => 'created'], 201);
        }

        $path = FileSystem::storeFile($icon, 'brand/icons');

        $result = Brand::create([

            'uuid' => $uuid,

            'name' => $name,

            'requested' => 0,

            'icon' =>  $path,

        ]);

        if ($result) {

            return response($result, 201);
        }

        return response(['message' => 'not acceptable'], 406);
    }

    public function edit($uuid)

    {

        return Brand::where('uuid', $uuid)->first();
    }

    public function update($credentials, $icon)

    {



        $exist = Brand::where('name', $credentials['name'])->first();

        $path = FileSystem::storeFile($icon, 'Brand/icons');

        if ($exist) {

            if ($path) {

                try {

                    $result = Brand::where('uuid', $credentials['uuid'])->update([

                        'icon' => $path

                    ]);
                } catch (Exception $e) {

                    Log::error($e);

                    $result = false;
                }
            }
        } else {

            if ($path) {

                try {

                    $result = Brand::where('uuid', $credentials['uuid'])->update([

                        'name' => $credentials['name'],

                        'icon' => $path

                    ]);
                } catch (Exception $e) {

                    Log::error($e);

                    $result = false;
                }
            }
        }





        if ($result) {

            return response(['message' => 'accepted'], 202);
        }

        return response(['message' => 'not accepted'], 406);
    }

    public function delete($uuid)

    {

        $result = Brand::where('uuid', $uuid)->delete();

        if ($result) {

            return response(['message' => 'success'], 410);
        }

        return response(['message' => 'not accepted', 406]);
    }

    // store featured brand 

    public function storeFeatured($credentils)

    {

        try {

            $result = FeaturedBrand::create([

                'uuid' => Str::uuid(),

                'brand_uuid' => $credentils['brand_uuid'],

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

    public function getAllFeaturedBrand()

    {

        return FeaturedBrand::with('brand')->orderBy('id', 'DESC')->get();
    }

    // delete featured brand 

    public function deleteFeatured($credentils)

    {

        try {

            $result = FeaturedBrand::where('uuid', $credentils['uuid'])->delete();
        } catch (Exception $e) {

            log::error($e);

            $result = false;
        }

        if ($result) {

            return response(['message' => 'deleted'], 410);
        }

        return response(['message' => 'not acceptable'], 406);
    }

    public function getAllCampaignBrand()

    {

        return BrandCampaign::orderBy('id', 'DESC')->get();
    }
    // // campaign product list by brand campaign uuid
    public function getAllCampaignBrandProduct($credentials)

    {
        $exists = BrandCampaign::where('uuid', $credentials['uuid'])->first();
        return Product::whereIn('brand_uuid', json_decode($exists->brand_uuid))->select('name', 'uuid')->with('details:product_uuid,price,cover,stock,discount,discount_type,discount_duration', 'details.cover')->orderBy('id', 'DESC')->paginate(30);
    }

    //store brand campaign

    public function storeCampaign($credentils, $cover)

    {

        $array = array();

        foreach ($credentils['brand_uuid'] as $categoryUuid) {

            $exist = Brand::where('uuid', $categoryUuid)->first();

            if (!$exist) {

                return response(['message' => 'uuid not found'], 404);
            }

            array_push($array, $categoryUuid);
        }

        $path = FileSystem::storeFile($cover, 'campaign/brand/cover');

        if ($path) {

            try {

                $result = BrandCampaign::create([

                    'uuid' => Str::uuid(),

                    'brand_uuid' => json_encode($array),

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

    // delete brand campaign 

    public function deleteCampaign($credentils)

    {

        try {

            $result = BrandCampaign::where('uuid', $credentils['uuid'])->delete();
        } catch (Exception $e) {

            log::error($e);

            $result = false;
        }

        if ($result) {

            return response(['message' => 'deleted'], 410);
        }

        return response(['message' => 'not acceptable'], 406);
    }
}
