<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class InvoiceRepositoryServicesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'InvoiceRepositoryServices';
    }
}
