<?php

namespace App\Repositories;

interface PostGalleryRepositoryInterface
{
    public function createPost($token, $file);
    public function storePost($type, $path, $token);
    public function deletePostGallery($credentils, $token);
}
