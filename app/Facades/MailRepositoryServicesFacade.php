<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MailRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'MailRepositoryServices';
    }
}
