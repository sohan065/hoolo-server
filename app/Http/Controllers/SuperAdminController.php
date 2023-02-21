<?php

namespace App\Http\Controllers;

use Superadmin;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function createSuperAdmin(Request $request)
    {
        $data = 1234;
        return Superadmin::createSuperAdmin($data);
    }
}
