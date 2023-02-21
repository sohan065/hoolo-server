<?php

namespace App\Http\Controllers;

use Creator;
use Validator;
use Illuminate\Http\Request;

class CreatorController extends Controller
{
    public function creatorRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'bail|required|string|min:5',
            'phone' => 'bail|required|string|min:8|max:15',
            'country_code' => 'bail|required|string',
            'email' => 'bail|required|email|unique:users,email',
            'user_name' => 'bail|required|string|min:3',
            'country_uuid' => 'bail|required|string|exists:countries,uuid',
            'state_uuid' => 'bail|required|string|exists:states,uuid',
            'city_uuid' => 'bail|required|string|exists:cities,uuid',
            'thana_uuid' => 'bail|nullable|string|exists:thanas,uuid',
            'post_code_uuid' => 'bail|required|string|exists:post_codes,uuid',
            'about' => 'bail|required|string|min:3',
            'certification' => 'bail|required|array|min:1',

        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['full_name', 'phone', 'country_code', 'email', 'user_name', 'country_uuid', 'state_uuid', 'city_uuid', 'thana_uuid', 'post_code_uuid', 'about']);
        $cretification = $request->file('certification');
        return Creator::creatorRegistration($validated, $cretification);
    }
    function verification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email|exists:users,email',
            'verification_code' => 'bail|required|exists:users,email_verification_code',
            'phone' => 'bail|required|string|exists:users,phone',
            'otp' => 'bail|required|string|exists:users,otp',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['email', 'verification_code', 'phone', 'otp']);
        return Creator::verification($validated);
    }

    function resendEmailVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email|exists:users,email',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['email']);
        return Creator::resendEemailVerification($validated);
    }

    function resendPhoneVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'bail|required|string|exists:users,phone',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['phone']);
        return Creator::resendPhoneVerification($validated);
    }

    function creatorLogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'phone' => 'bail|required|string|exists:users,phone',
            'country_code' => 'bail|required|string',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only('phone');
        return Creator::attempt($validated);
    }
    function creatorLoginSuccess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'bail|required|string|exists:users,phone',
            'otp' => 'bail|required|string|exists:users,otp',
            'mac_id' => 'bail|required|string',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only('phone', 'otp','mac_id');
        return Creator::logInSuccess($validated);
    }
}
