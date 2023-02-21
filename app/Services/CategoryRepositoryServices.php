<?php

namespace App\Services;

use Exception;
use FileSystem;
use App\Models\Category;
use App\Models\GCategory;
use App\Models\GCategoryCampaign;
use App\Models\PCategory;
use App\Models\DpCategory;
use Illuminate\Support\Str;
use App\Models\CategoryCampaign;
use App\Models\FeaturedCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Repositories\CategoryRepositoryInterface;


class CategoryRepositoryServices implements CategoryRepositoryInterface
{
    public function getAllGcategory()
    {  
        return GCategory::where('is_active',1)->with(['pcategory' => function ($query) {
                $query->where('is_active', 1);
            },'pcategory.category'])->get();;
       
    }
    public function storeGcategory($credentils, $icon)
    {
        $path = $icon;
        if ($icon) {
            $path = FileSystem::storeFile($icon, 'gcategory/icons');
        }
        try {
            $uuid = Str::uuid();
            $name = $credentils['name'];
            $result = GCategory::create([
                'name' => $name,
                'uuid' => $uuid,
                'icon' => $path,
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response($result, 201);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public function editGcategory($uuid)
    {
        return GCategory::where('uuid', $uuid)->first();
    }
    public function updateGcategory($credentils, $icon)
    {
        $path = $icon;
        $exist = GCategory::where('name', $credentils['name'])->first();
        if ($exist) {
            return response(['message' => 'already exists'], 302);
        }
        $exist = GCategory::where('uuid', $credentils['uuid'])->first();
        if ($icon) {
            if ($exist['icon']) {
                $deleteOldIcon = FileSystem::deleteFile($exist['icon']);
            }
            $path = FileSystem::storeFile($icon, 'gcategory/icons');
        } else {
            if ($exist['icon']) {
                $deleteOldIcon = FileSystem::deleteFile($exist['icon']);
            }
        }
        try {
            $result = GCategory::where('uuid', $credentils['uuid'])->update([
                'name' => $credentils['name'],
                'icon' => $path,
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response($result, 200);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public function deleteGcategory($uuid)
    {
        // to do
    }
    public function getAllPcategory()
    {
       return $data = PCategory::orderBy('id', 'DESC')->get();
       
    }
    public function storePcategory($credentils, $icon)
    {
        $path = $icon;
        if ($icon) {
            $path = FileSystem::storeFile($icon, 'gcategory/icons');
        }
        try {
            $uuid = Str::uuid();
            $name = $credentils['name'];
            $gcategory_uuid = $credentils['gcategory_uuid'];
            $result = PCategory::create([
                'name' => $name,
                'uuid' => $uuid,
                'gcategory_uuid' => $gcategory_uuid,
                'icon' => $path,
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response($result, 201);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public function editPcategory($uuid)
    {
        return PCategory::where('uuid', $uuid)->first();
    }
    public function updatePcategory($credentils, $icon)
    {
        $path = $icon;
        $exist = PCategory::where('name', $credentils['name'])->first();
        if ($exist) {
            return response(['message' => 'already exists'], 302);
        }
        $exist = PCategory::where('uuid', $credentils['uuid'])->first();
        if ($icon) {
            if ($exist['icon']) {
                $deleteOldIcon = FileSystem::deleteFile($exist['icon']);
            }
            $path = FileSystem::storeFile($icon, 'pcategory/icons');
        } else {
            if ($exist['icon']) {
                $deleteOldIcon = FileSystem::deleteFile($exist['icon']);
            }
        }
        try {
            $result = PCategory::where('uuid', $credentils['uuid'])->update([
                'name' => $credentils['name'],
                'icon' => $path,
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response($result, 202);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public function deletePcategory($uuid)
    {
        // to do
    }
    public function getAllCategory()
    {   return Category::with('pcategory.gcategory')->orderBy('id', 'DESC')->get();
        //  $seconds=60*10;
        //  return Cache::remember('getallcategory', $seconds, function () {
        //     return Category::with('pcategory.gcategory')->get();
        // });
    }
    public function storeCategory($credentils, $icon)
    {
        $path = $icon;
        if ($icon) {
            $path = FileSystem::storeFile($icon, 'gcategory/icons');
        }
        try {
            $uuid = Str::uuid();
            $name = $credentils['name'];
            $pcategory_uuid = $credentils['pcategory_uuid'];
            $result = Category::create([
                'name' => $name,
                'uuid' => $uuid,
                'pcategory_uuid' => $pcategory_uuid,
                'icon' => $path,
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response($result, 201);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public function editCategory($uuid)
    {
        return Category::where('uuid', $uuid)->first();
    }
    public function updateCategory($credentils, $icon)
    {
        $path = $icon;
        $exist = Category::where('name', $credentils['name'])->first();
        if ($exist) {
            return response(['message' => 'already exists'], 302);
        }
        $exist = Category::where('uuid', $credentils['uuid'])->first();
        if ($icon) {
            if ($exist['icon']) {
                $deleteOldIcon = FileSystem::deleteFile($exist['icon']);
            }
            $path = FileSystem::storeFile($icon, 'category/icons');
        } else {
            if ($exist['icon']) {
                $deleteOldIcon = FileSystem::deleteFile($exist['icon']);
            }
        }
        try {
            $result = Category::where('uuid', $credentils['uuid'])->update([
                'name' => $credentils['name'],
                'icon' => $path,
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response($result, 202);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public function deleteCategory($uuid)
    {
        // to do
    }
    public function getAllDpCategory()
    {
        return  $data = DpCategory::orderBy('id', 'DESC')->get();
    }
    public function storeDpCategory($credentils,$icon)
    {
        $path= $icon ? FileSystem::storeFile($icon,'dpcategory/icon'): null;
        try {
            
            $result = DpCategory::create([
                'uuid' => Str::uuid(),
                'name' => $credentils['name'],
                'icon' =>  $path,
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response($result, 201);
        }
        $deletePath=FileSystem::deleteFile($path);
        return response(['message' => 'not acceptable'], 406);
    }
    public function editDpCategory($uuid)
    {
        return DpCategory::where('uuid', $uuid)->first();
    }
    public function updateDpCategory($credentils)
    {
        $exist = DpCategory::where('name', $credentils['name'])->first();
        if ($exist) {
            return response(['message' => 'already exists'], 302);
        }
        try {
            $result = DpCategory::where('uuid', $credentils['uuid'])->update([
                'name' => $credentils['name'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            return response($result, 202);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public function deleteDpCategory($uuid)
    {
        //to  do
    }
    // store featured category 
    public function storeFeatured($credentils, $cover)
    {
        $path = FileSystem::storeFile($cover, 'featured/cover');
        if ($path) {
            try {
                $result = FeaturedCategory::create([
                    'uuid' => Str::uuid(),
                    'category_uuid' => $credentils['category_uuid'],
                    'cover' => $path,
                ]);
            } catch (Exception $e) {
                Log::error($e);
                $result = false;
            }
            if ($result) {
                return response($result, 201);
            }
        }
        return response(['message' => 'not acceptable'], 406);
    }
    public function getAllFeaturedCategory()
    {    return FeaturedCategory::with('category.pcategory.gcategory')->get();
    //     $seconds=60*10;
    //     return Cache::remember('getAllFeaturedCategory', $seconds, function () {
    //     return FeaturedCategory::with('category.pcategory.gcategory')->get();
    // });
        
    }
    // delete featured category 
    public function deleteFeatured($credentils)
    {
        try {
            $result = FeaturedCategory::where('uuid', $credentils['uuid'])->delete();
        } catch (Exception $e) {
            log::error($e);
            $result = false;
        }
        if ($result) {
            return response(['message' => 'deleted'], 410);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    // get all campaign category
    public function getAllCampaignCategory()
    {      return CategoryCampaign::all();
        //     $seconds=60*10;
        //     return Cache::remember('getAllCampaignCategory', $seconds, function () {
        //     return CategoryCampaign::all();
        // });
        
    }
    // get all grand campaign category
    public function getAllGrandCampaignCategory()
    {      
        return GCategoryCampaign::all();
    }
    // store campaign category
    public function storeCampaign($credentils, $cover)
    {
        $array = array();
        foreach ($credentils['category_uuid'] as $categoryUuid) {
            $exist = Category::where('uuid', $categoryUuid)->first();
            if (!$exist) {
                return response(['message' => 'uuid not found'], 404);
            }
            array_push($array, $categoryUuid);
        }
        $path = FileSystem::storeFile($cover, 'campaign/category/cover');
        if ($path) {
            try {
                $result = CategoryCampaign::create([
                    'uuid' => Str::uuid(),
                    'category_uuid' => json_encode($array),
                    'title' => $credentils['title'],
                    'cover' => $path,
                ]);
            } catch (Exception $e) {
                Log::error($e);
                $result = false;
            }
            if ($result) {
                return response($result, 201);
            }
            $deleteFile = FileSystem::deleteFile($path);
        }
        return response(['message' => 'not acceptable'], 406);
    }
     // store grand category campaign 
    public function storeGrandCampaign($credentils, $cover)
    {
        $array = array();
        foreach ($credentils['uuid'] as $categoryUuid) {
            $exist = Category::where('uuid', $categoryUuid)->first();
            if (!$exist) {
                $response = ['name' => array('invalid category uuid')];
                return response($response, 406);
            }
            array_push($array, $categoryUuid);
        }
        $path = FileSystem::storeFile($cover, 'campaign/grand/cover');
        if ($path) {
            try {
                $result = GCategoryCampaign::create([
                    'uuid' => Str::uuid(),
                    'g_category_uuid' => json_encode($array),
                    'title' => $credentils['title'],
                    'cover' => $path,
                ]);
            } catch (Exception $e) {
                Log::error($e);
                $result = false;
            }
            if ($result) {
                return response($result, 201);
            }
            $deleteFile = FileSystem::deleteFile($path);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    // delete campaign category
    public  function deleteCampaign($credentils)
    {
        try {
            $result = CategoryCampaign::where('uuid', $credentils['uuid'])->delete();
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
