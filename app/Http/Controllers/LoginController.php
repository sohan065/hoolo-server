<?php

namespace App\Http\Controllers;

use Sms;
use Merchant;
use Exception;
use Validator;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    function merchantLogin(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'email' => 'bail|required|string|exists:merchants,email',
                'password' => 'bail|required||string|min:8',
            ]);
            if ($validator->fails()) {
                return response($validator->messages(), 422);
            }
            $validated = $request->only('email', 'password');
            return Merchant::attempt($validated);
        } catch (Exception $e) {
            return $e;
        }
    }

    function forgetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email|exists:merchants,email',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['email']);
        return Merchant::forgetPassword($validated);
    }

    function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => 'bail|required|string|exists:reset_passwords,verification_code',
            'password' => 'bail|required|string|min:6|max:32|confirmed',
            'password_confirmation' => 'bail|required|string|min:6|max:32'
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['verification_code', 'password']);
        return Merchant::resetPassword($validated);
    }

    function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|string|exists:merchants,email',
            'oldPassword' => 'bail|required|string|min:8|max:32',
            'newPassword' => 'bail|required|string|min:8|max:32|confirmed',
            'newPassword_confirmation' => 'bail|required|string|min:8|max:32',
        ]);
        $header = $request->header('token');
        if ($validator->fails()) {
            return  response($validator->messages(), 422);
        }
        $validated = $request->only(['email', 'oldPassword', 'newPassword']);
        return Merchant::changePassword($validated, $header);
    }

    function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contacts' => 'bail|required',
            'msg' => 'bail|required|string|max:50',
        ]);
        if ($validator->fails()) {
            return  response($validator->messages(), 422);
        }

        $validated = $request->only(['contacts', 'msg']);

        return Sms::sendSms($validated);
    }
}
