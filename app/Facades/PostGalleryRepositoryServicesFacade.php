<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PostGalleryRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PostGalleryRepositoryServices';
    }
}
