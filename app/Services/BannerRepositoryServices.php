<?php

namespace App\Services;

use Exception;
use FileSystem;
use App\Models\Banner;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Repositories\BannerRepositoryInterface;

class BannerRepositoryServices implements BannerRepositoryInterface
{
    public function getAllBanner()
    {
        return Banner::all();
    }
    public function createBanner($credentials, $file)
    {
        $storeFile = FileSystem::storeFile($file, 'banner');
        if ($storeFile) {
            try {
                $result = Banner::create([
                    'uuid' => Str::uuid(),
                    'path' => $storeFile,
                    'type' => $credentials['type'],
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
    public function deleteBanner($uuid)
    {
        $exist = Banner::where('uuid', $uuid)->first();
        try {
            $result = Banner::where('uuid', $uuid)->delete();
        } catch (Exception $e) {
            log::error($e);
            $result = false;
        }
        if ($result) {
            $deleteFile = FileSystem::deleteFile($exist['path']);
            return response(['message' => 'deleted'], 410);
        }
        return response(['message' => 'not acceptable'], 406);
    }
}
