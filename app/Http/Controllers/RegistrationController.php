<?php

namespace App\Http\Controllers;

use Validator;
use Merchant;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    function merchantReg(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'full_name' => 'bail|required|string|min:5',
            'phone' => 'bail|required|string|min:8|max:15',
            'email' => 'bail|required|email|unique:merchants,email',
            'user_name' => 'bail|required|string|min:3|unique:merchants,user_name',
            'password' => 'bail|required|string|min:6|max:32|confirmed',
            'password_confirmation' => 'bail|required|string|min:6|max:32',
            'country_uuid' => 'bail|required|string|exists:countries,uuid',
            'state_uuid' => 'bail|required|string|exists:states,uuid',
            'city_uuid' => 'bail|required|string|exists:cities,uuid',
            'thana_uuid' => 'bail|nullable|string|exists:thanas,uuid',
            'post_code_uuid' => 'bail|required|string|exists:post_codes,uuid',
            'about' => 'bail|required|string|min:20',
            'company_name' => 'bail|required|string|min:2',
            'company_logo' => 'bail|required|image',
            'company_banner' => 'bail|nullable|image',
            'website' => 'bail|nullable|string|min:5',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['full_name', 'phone', 'email', 'user_name', 'password', 'country_uuid', 'state_uuid', 'city_uuid', 'thana_uuid', 'post_code_uuid', 'about', 'company_name', 'website']);
        $company_logo = $request->file('company_logo');
        $company_banner = $request->file('company_banner');
        return Merchant::registration($validated, $company_logo, $company_banner);
    }

    function verification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email|exists:merchants,email',
            'verification_code' => 'bail|required|exists:merchants,email_verification_code',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['email', 'verification_code']);
        return Merchant::verification($validated);
    }

    function resendVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email|exists:merchants,email',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['email']);
        return Merchant::resendVerification($validated);
    }
}
