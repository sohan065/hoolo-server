<?php

namespace App\Repositories;

interface BannerRepositoryInterface
{
    public function getAllBanner();
    public function createBanner($credentials, $file);
    public function deleteBanner($uuid);
}
