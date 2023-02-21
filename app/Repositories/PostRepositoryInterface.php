<?php

namespace App\Repositories;

interface PostRepositoryInterface
{
    public function getAllPost();
    public function increaseView($validated);
    public function store($credentials, $token);
    public function delete($credentials, $token);
    public function storePostLike($credentials, $token);
    public function storePostComment($credentials, $token, $file);
}
