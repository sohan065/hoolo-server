<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Applicationauth;

class AppRegistrationController extends Controller
{
    function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'bail|required|string|min:3|unique:registered_apps,app_name',
            'domain' => 'bail|nullable|string|min:5',
            'email' => 'bail|required|email|unique:registered_apps,email',
            'phone' => 'bail|required|alpha_num|min:8|max:15',
            'platform' => 'bail|required|string|min:3',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['app_name', 'domain', 'email', 'phone', 'platform']);
        return Applicationauth::registration($validated);
    }

    function active($uuid)
    {
        return Applicationauth::active($uuid);
    }

    function inctive($uuid)
    {
        return Applicationauth::inactive($uuid);
    }

    function delete($uuid)
    {
        return Applicationauth::delete($uuid);
    }
}
