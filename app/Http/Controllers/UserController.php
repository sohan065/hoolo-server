<?php



namespace App\Http\Controllers;



use User;

use Validator;

use Illuminate\Http\Request;



class UserController extends Controller

{
    public function changeUserName(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'full_name' => 'bail|nullable|string',
            'user_name' => 'bail|nullable|string',
            'email' => 'bail|nullable|string',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['full_name', 'user_name','email']);
        $token = $request->header('token');

        return User::changeUserName($validated, $token);
    }
    public function logout(Request $request)
    {

        $token = $request->header('token');

        return User::logout($token);
    }
    public function getAllUserInfo()
    {

        return User::getAllUserInfo();
    }
    public function getUserInfo(Request $request)
    {

        $token = $request->header('token');

        return User::getUserInfo($token);
    }
    public function createUser(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'phone' => 'bail|required|string|max:20',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['phone']);

        return User::createUser($validated);
    }
    public function userVerification(Request $request)
    {



        $validator = Validator::make($request->all(), [

            'phone' => 'bail|required|string|max:14|exists:users,phone',

            'otp' => 'bail|required|string|max:4',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['otp', 'phone', 'mac_address']);



        return User::userVerification($validated);
    }
    public function infoStore(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'full_name' => 'bail|required|string|min:3|max:50',

            'user_name' => 'bail|required|string|min:3|max:20',

            'country_uuid' => 'bail|required|string|exists:countries,uuid',

            'state_uuid' => 'bail|required|string|exists:states,uuid',

            'city_uuid' => 'bail|required|string|exists:cities,uuid',

            'thana_uuid' => 'bail|nullable|string|exists:thanas,uuid',

            'post_code_uuid' => 'bail|required|string|exists:post_codes,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $token = $request->header('token');

        $validated = $request->only(['user_uuid', 'full_name', 'user_name', 'country_uuid', 'state_uuid', 'city_uuid', 'thana_uuid', 'post_code_uuid']);

        return User::createUserInfo($validated, $token);
    }
    public function editInfo(Request $request)
    {



        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:user_infos,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return User::editInfo($validated);
    }
    public function updateInfo(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:user_infos,uuid',

            'full_name' => 'bail|required|string|min:3|max:50',

            'user_name' => 'bail|required|string|min:3|max:20',

            'country_uuid' => 'bail|required|string|exists:countries,uuid',

            'state_uuid' => 'bail|required|string|exists:states,uuid',

            'city_uuid' => 'bail|required|string|exists:cities,uuid',

            'thana_uuid' => 'bail|nullable|string|exists:thanas,uuid',

            'post_code_uuid' => 'bail|required|string|exists:post_codes,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid', 'full_name', 'user_name', 'country_uuid', 'state_uuid', 'city_uuid', 'thana_uuid', 'post_code_uuid']);

        return User::updateInfo($validated);
    }
    public function deleteInfo(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:user_infos,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return User::deleteInfo($validated);
    }
    public function profileStore(Request $request)
    {
         

        $validator = Validator::make($request->all(), [

            'image' => 'bail|required|image|mimes:jpg,jpeg,png',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $file = $request->file('image');

        $token = $request->header('token');

        return User::createUserProfile($token, $file);
    }
    public function deleteProfile(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:user_profiles,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return User::deleteProfile($validated);
    }
    public function getAllOrders(Request $request)
    {
        $token = $request->header('token');
        return User::getAllOrders($token);
    }
    public function getConfirmOrders(Request $request)
    {
        $token = $request->header('token');
        return User::getConfirmOrders($token);
    }
    public function getPendingOrders(Request $request)
    {
        $token = $request->header('token');
        return User::getPendingOrders($token);
    }
    public function getCancelOrders(Request $request)
    {
        $token = $request->header('token');
        return User::getCancelOrders($token);
    }
    public function getShippedOrders(Request $request)
    {
        $token = $request->header('token');
        return User::getShippedOrders($token);
    }
    public function likedPosts(Request $request){
        $token = $request->header('token');
        return User::likedPosts($token);
    }
    
    // refund from user 
    public function refundPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'order_code' => 'bail|required|string|exists:product_order_payments,order_code',
        ]);
        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $token = $request->header('token');
        $validated=$request->only(['order_code']);

        return User::refundPayment($token, $validated);
    }
    
}



// 