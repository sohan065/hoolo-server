<?php



namespace App\Services;



use Sms;

use Token;
use Bkash;
use Ssl;
use Exception;

use FileSystem;

use Carbon\Carbon;

use App\Models\User;
use App\Models\PostLike;

use App\Models\UserInfo;
use App\Models\ProductOrderDetail;
use App\Models\ProductOrderPayment;

use App\Models\UserProfile;

use Illuminate\Support\Str;

use App\Models\UserAccessToken;

use Illuminate\Support\Facades\Log;

use Jenssegers\Agent\Facades\Agent;

use App\Repositories\UserRepositoryInterface;





class UserRepositoryServices implements UserRepositoryInterface

{
    public function changeUserName($credentials, $token)
    {

        $tokenInfo = Token::decode($token);
        if(array_key_exists('full_name', $credentials) && $credentials['full_name'] ){
            UserInfo::updateOrCreate(
                ['user_uuid' =>$tokenInfo['uuid']],
                ['full_name' => $credentials['full_name'],'uuid'=>Str::uuid()]
            );
            return response(['messsage' => 'updated'], 202);
        }
        else if(array_key_exists('user_name', $credentials) && $credentials['user_name']){
            UserInfo::updateOrCreate(
                ['user_uuid' =>$tokenInfo['uuid']],
                ['user_name' => $credentials['user_name'],'uuid'=>Str::uuid()]
            );
            return response(['messsage' => 'updated'], 202);
        }
         else if(array_key_exists('email', $credentials) && $credentials['email']){
            User::where('uuid',$tokenInfo['uuid'])->update([
                     'email'=>$credentials['email'],
                ]);
            return response(['messsage' => 'updated'], 202);
        }
        return response(['message' => 'not acceptable'], 406);
        
    }

    public function logout($token)

    {
        $tokenInfo = Token::decode($token);

        $deleteToken = UserAccessToken::where('user_uuid', $tokenInfo['uuid'])->where('mac_address', $tokenInfo['mac_address'])

            ->where('browser', $tokenInfo['browser'])->where('os', $tokenInfo['os'])->delete();

        return response(['message' => 'success'], 410);
    }

    public function getAllUserInfo()

    {
        return User::where('type', 0)->with('userInfo.profile')->orderBy('id', 'DESC')->paginate(10);
    }

    public function getUserInfo($token)

    {

        $token_info = Token::decode($token);

        return User::where('uuid', $token_info['uuid'])->with('userInfo.profile')->first();
    }

    public function createUser($credentials)

    {

        $otp = rand(1111, 9999);

        $update = User::where('phone', $credentials['phone'])->update(['otp' => $otp]);

        if ($update) {

            $this->sendOtp($credentials['phone'], $otp);

            return response(['message' => 'success'], 200);
        }

        try {

            $result = User::create([

                'uuid' => Str::uuid(),

                'phone' => $credentials['phone'],

                'otp' => $otp,

                'type' => 0,

            ]);
        } catch (Exception $e) {

            Log::error($e);

            $result = false;
        }

        if ($result) {

            $this->sendOtp($credentials['phone'], $otp);

            return response(['message' => 'success'], 200);
        }

        return response(['message' => 'not acceptable'], 406);
    }

    public function userVerification($credentials)

    {

        try {

            $valid =  User::where('phone', $credentials['phone'])->where('otp', $credentials['otp'])->update([

                'is_active' => 1,

                'status' => 1,

                'is_banned' => 0,

                'otp' => null,

            ]);
        } catch (Exception $e) {

            log::error($e);

            $valid = false;
        }

        if ($valid) {

            $exist = User::where('phone', $credentials['phone'])->first();

            $os = Agent::platform();

            $browser = Agent::browser();

            $macAddress = $credentials['mac_address'];

            $ipAddress = request()->ip();

            $tokenData = [

                'uuid' => $exist->uuid,

                'phone' => $exist->phone,

                'os' => $os,

                'browser' => $browser,

                'mac_address' => $macAddress,

                'ip_address' =>  $ipAddress,

                'type' => $exist->type,

                'time' => Carbon::now()

            ];

            $token = Token::create($tokenData);

            if ($token) {

                try {

                    $result = UserAccessToken::updateOrCreate(

                        ['user_uuid' => $exist->uuid, 'os' => $os, 'browser' => $browser, 'mac_address' => $macAddress],

                        ['token' => $token, 'ip_address' => $ipAddress]

                    );
                } catch (Exception $e) {

                    log::error($e);

                    $result = false;
                }

                if ($result) {

                    return response(['token' => $token, 'uuid' => $exist->uuid, 'type' => $exist->type], 202);
                }
            }
        }

        return response(['message' => 'not acceptable'], 406);
    }

    public function createUserInfo($credentials, $token)

    {

        try {

            $tokenInfo = Token::decode($token);

            $result = UserInfo::create([

                'uuid' => Str::uuid(),

                'user_uuid' => $tokenInfo['uuid'],

                'full_name' => $credentials['full_name'],

                'user_name' => $credentials['user_name'],

                'country_uuid' => $credentials['country_uuid'],

                'state_uuid' => $credentials['state_uuid'],

                'city_uuid' => $credentials['city_uuid'],

                'thana_uuid' => $credentials['thana_uuid'] ? $credentials['thana_uuid'] : null,

                'post_code_uuid' => $credentials['post_code_uuid'],

            ]);
        } catch (Exception $e) {

            log::error($e);

            $result = false;
        }

        if ($result) {

            return response($result, 202);
        }

        return response(['message' => 'not acceptable'], 406);
    }

    public function editInfo($credentials)

    {

        try {

            $result = UserInfo::where('uuid', $credentials['uuid'])->first();
        } catch (Exception $e) {

            log::error($e);

            $result = false;
        }

        if ($result) {

            return response($result, 202);
        }

        return response(['message' => 'not found'], 404);
    }

    public function updateInfo($credentials)

    {

        try {

            $result = UserInfo::where('uuid', $credentials['uuid'])->update([

                'full_name' => $credentials['full_name'],

                'user_name' => $credentials['user_name'],

                'country_uuid' => $credentials['country_uuid'],

                'state_uuid' => $credentials['state_uuid'],

                'city_uuid' => $credentials['city_uuid'],

                'thana_uuid' => $credentials['thana_uuid'] ? $credentials['thana_uuid'] : null,

                'post_code_uuid' => $credentials['post_code_uuid'],

            ]);
        } catch (Exception $e) {

            log::error($e);

            $result = false;
        }

        if ($result) {

            return response(['message' => 'success'], 202);
        }

        return response(['message' => 'not acceptable'], 406);
    }

    public function deleteInfo($credentials)

    {

        try {

            $result = UserInfo::where('uuid', $credentials['uuid'])->first()->delete();
        } catch (Exception $e) {

            log::error($e);

            $result = false;
        }

        if ($result) {

            return response(['message' => 'success'], 202);
        }

        return response(['message' => 'not acceptable'], 406);
    }

    public function createUserProfile($token, $file)

    {
    
        $token_info = Token::decode($token);
        $existProfile = UserProfile::where('user_uuid', $token_info['uuid'])->first();
        if ($existProfile) {
            $deletePath = FileSystem::deleteFile($existProfile->path);
            $path = FileSystem::storeFile($file, 'user/profiles');
            $existProfile->path = $path;
            $existProfile->update();
            return response(['message' => 'updated'], 202);
        }
        $path = FileSystem::storeFile($file, 'user/profiles');

        if ($path) {

            try {

                $result = UserProfile::create([

                    'uuid' => Str::uuid(),

                    'user_uuid' => $token_info['uuid'],

                    'path' => $path,

                ]);
            } catch (Exception $e) {

                log::error($e);

                $result = false;
            }

            if ($result) {

                return response($result, 202);
            }
        }

        return response(['message' => 'not acceptable'], 406);
    }

    public function deleteProfile($credentials)

    {

        $exist = UserProfile::where('uuid', $credentials['uuid'])->first();

        if ($exist) {

            $path = $exist['path'];
        }

        try {

            $result = UserProfile::where('uuid', $credentials['uuid'])->first()->delete();
        } catch (Exception $e) {

            log::error($e);

            $result = false;
        }

        if ($result) {

            $deletePath = FileSystem::deleteFile($path);

            return response(['message' => 'success'], 202);
        }

        return response(['message' => 'not acceptable'], 406);
    }

    protected function sendOtp($phone, $otp)

    {

        $array = ['contacts' => $phone, 'msg' => 'Your OTP CODE for hoolo.live is ' . $otp];

        return  Sms::sendSms($array);
    }
    public function  getAllOrders($token)
    {
        $tokenInfo = Token::decode($token);
        return ProductOrderDetail::where('user_uuid', $tokenInfo['uuid'])->with(
            'payment',
            'order.product:uuid,name',
            'order.product.details:product_uuid,cover',
            'order.product.details.cover',
            'country',
            'state:uuid,name',
            'city:uuid,name',
            'thana:uuid,name',
            'postCode:uuid,name'
        )->orderBy('id', 'desc')->paginate(30);
    }
    public function  getConfirmOrders($token)
    {
        $tokenInfo = Token::decode($token);
        return ProductOrderDetail::where('user_uuid', $tokenInfo['uuid'])->where('order_status', 1)->with(
            'payment',
            'order.product:uuid,name',
            'order.product.details:product_uuid,cover',
            'order.product.details.cover',
            'country',
            'state:uuid,name',
            'city:uuid,name',
            'thana:uuid,name',
            'postCode:uuid,name'
        )->orderBy('id', 'desc')->paginate(30);
    }
    public function  getPendingOrders($token)
    {
        $tokenInfo = Token::decode($token);
        return ProductOrderDetail::where('user_uuid', $tokenInfo['uuid'])->where('order_status', 0)->with(
            'payment',
            'order.product:uuid,name',
            'order.product.details:product_uuid,cover',
            'order.product.details.cover',
            'country',
            'state:uuid,name',
            'city:uuid,name',
            'thana:uuid,name',
            'postCode:uuid,name'
        )->orderBy('id', 'desc')->paginate(30);
    }
    public function  getCancelOrders($token)
    {
        $tokenInfo = Token::decode($token);
        return ProductOrderDetail::where('user_uuid', $tokenInfo['uuid'])->where('order_status', 2)->with(
            'payment',
            'order.product:uuid,name',
            'order.product.details:product_uuid,cover',
            'order.product.details.cover',
            'country',
            'state:uuid,name',
            'city:uuid,name',
            'thana:uuid,name',
            'postCode:uuid,name'
        )->orderBy('id', 'desc')->paginate(30);
    }
    public function  getShippedOrders($token)
    {
        $tokenInfo = Token::decode($token);
        return ProductOrderDetail::where('user_uuid', $tokenInfo['uuid'])->where('delivery_status', 3)->with(
            'payment',
            'order.product:uuid,name',
            'order.product.details:product_uuid,cover',
            'order.product.details.cover',
            'country',
            'state:uuid,name',
            'city:uuid,name',
            'thana:uuid,name',
            'postCode:uuid,name'
        )->orderBy('id', 'desc')->paginate(30);
    }
    public function likedPosts($token)
    {
        $tokenInfo = Token::decode($token);
        return PostLike::select('post_uuid')->whereJsonContains('user_uuid', $tokenInfo['uuid'])->with('post')->get();
    }
    
    public function refundPayment($token,$orderCode){
        
        $tokenInfo=Token::decode($token);
        $orderInfo=ProductOrderPayment::where('user_uuid',$tokenInfo['uuid'])->where('order_code',$orderCode)->with('orders:order_code,price,quantity','orderDetails:order_code,shipping_cost')->first();
        
        if($orderInfo){
            $totalAmount  = $orderInfo->orderDetails->shipping_cost;
            foreach($orderInfo->orders as $order){
                $totalAmount += $order->price*$order->quantity;
            }
        }
        if($orderInfo && $orderInfo['payment_method']=='Bkash'){
            // $orderCode, $trxNumber, $paymentId, $amount
            return Bkash::refund($orderCode,$orderInfo->trx_number,$orderInfo->payment_id,$totalAmount);
        }
        if($orderInfo && $orderInfo['payment_method']=='Card'){
             return "ssl services for refund";
        }
        
    }
    
}
