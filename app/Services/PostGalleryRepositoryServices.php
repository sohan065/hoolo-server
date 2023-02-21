<?php

namespace App\Services;

use Token;
use Exception;
use FileSystem;
use App\Models\PostGallery;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Repositories\PostGalleryRepositoryInterface;

class PostGalleryRepositoryServices implements PostGalleryRepositoryInterface
{
    // store post
    public function createPost($token, $file)
    {
        $token = Token::decode($token);
        $file_check = $file->extension();
        if ($file_check == 'jpg') {
            $path =  FileSystem::storeFile($file, 'post/images');
            $type = 0;
        } else {
            $path =  FileSystem::storeFile($file, 'post/videos');
            $type = 1;
        }
        $result = $this->storePost($type, $path, $token);
        if ($result) {
            return response($result, 201);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    //store post Gallery
    public function storePost($type, $path, $token)
    {
        try {
            $result =  PostGallery::create([
                'uuid' => Str::uuid(),
                'merchant_uuid' => $token['uuid'],
                'path' => $path,
                'type' => $type,
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        return $result;
    }
    // delete post gallery
    public function deletePostGallery($credentils, $token)
    {
        $tokenInfo = Token::decode($token);
        $exists = PostGallery::where('uuid', $credentils['uuid'])->where('merchant_uuid', $tokenInfo['uuid'])->first();
        if ($exists) {
            $path = $exists['path'];
            $deletePath = FileSystem::deleteFile($path);
            try {
                $result =   PostGallery::where('uuid', $credentils['uuid'])->where('merchant_uuid', $tokenInfo['uuid'])->first()->delete();
            } catch (Exception $e) {
                log::error($e);
                $result = false;
            }
            if ($result) {
                return response(['message' => 'deleted'], 410);
            }
        }
        return response(['message' => 'not acceptable'], 406);
    }
}
