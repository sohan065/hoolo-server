<?php

namespace App\Services;

use App\Repositories\SuperAdminRepositoryInterface;

class SuperAdminRepositoryServices implements SuperAdminRepositoryInterface
{
    public function createSuperAdmin($credentials)
    {
        return "services";
    }
}
