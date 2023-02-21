<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function createUser($credentials);
    public function userVerification($credentials);
    public function createUserInfo($credentials, $tokenInfo);
    public function editInfo($credentials);
    public function updateInfo($credentials);
    public function deleteInfo($credentials);
    public function createUserProfile($token, $file);
    public function deleteProfile($credentials);
}
