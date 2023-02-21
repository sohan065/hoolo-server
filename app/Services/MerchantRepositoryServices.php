<?php



namespace App\Services;



use Log;

use Mail;

use Token;
use Exception;

use FileSystem;

use Carbon\Carbon;

use App\Models\Merchant;

use Illuminate\Support\Str;

use App\Models\Post;

use App\Models\Product;

use App\Models\MerchantInfo;
use App\Models\ProductGallery;

use App\Models\ResetPassword;

use App\Models\MerchantProfile;

use App\Models\MerchantAccessToken;

use Jenssegers\Agent\Facades\Agent;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

use App\Repositories\MerchantRepositoryInterface;



class MerchantRepositoryServices implements MerchantRepositoryInterface

{

    //Merchant  registration 
    public function registration($credentials, $company_logo, $company_banner)
    {

        $merchant = $this->storeMerchant($credentials);

        if ($merchant) {

            $banner = null;

            if ($company_banner) {

                $banner = FileSystem::storeFile($company_banner, 'stores/banner');
            }

            $logo = FileSystem::storeFile($company_logo, 'stores/logo');

            $merchantInfo = $this->storeInfo($credentials, $merchant->uuid, $logo, $banner);

            if ($merchantInfo) {

                $data = ['verification_code' => $merchant->email_verification_code, 'name' => $credentials['full_name']];

                Mail::send('Email Verification', $data, $credentials['email'], 'mail.verification');
                $merchant = Merchant::where('uuid',$merchantInfo->merchant_uuid)->with('info.country','info.state','info.city','info.thana', 'info.postcode','profile')->first();
                return response($merchant, 201);
            }

            Merchant::where('uuid', $merchant->uuid)->delete();

            FileSystem::deleteFile($banner);

            FileSystem::deleteFile($logo);
        }

        return response(['message' => 'not acceptable'], 406);
    }
    // merchant 
    public function storeMerchant($credentials)
    {

        try {

            $uuid = Str::uuid();

            $verification = Str::random(6);

            $result = Merchant::create([

                'uuid' => $uuid,

                'full_name' => $credentials['full_name'],

                'phone' => $credentials['phone'],

                'email' => $credentials['email'],

                'email_verification_code' => $verification,

                'user_name' => $credentials['user_name'],

                'password' => Hash::make($credentials['password']),

            ]);
        } catch (Exception $e) {

            Log::error($e);

            $result = false;
        }

        return $result;
    }
    //merchant info 
    public function storeInfo($credentials, $uuid, $logo, $banner)
    {

        $website = null;

        if (array_key_exists('website', $credentials)) {

            $website = $credentials['website'];
        }

        try {

            $result = MerchantInfo::create([

                'uuid' => Str::uuid(),

                'merchant_uuid' => $uuid,

                'country_uuid' => $credentials['country_uuid'],

                'state_uuid' => $credentials['state_uuid'],

                'city_uuid' => $credentials['city_uuid'],

                'thana_uuid' => $credentials['thana_uuid'],

                'post_code_uuid' => $credentials['post_code_uuid'],

                'about' => $credentials['about'],

                'company_name' => $credentials['company_name'],

                'company_logo' => $logo,

                'company_banner' => $banner,

                'website' => $website,

            ]);
        } catch (Exception $e) {

            Log::error($e);

            $result = false;
        }

        return $result;
    }
    // verification 
    public function verification($credentials)
    {

        $exist = Merchant::where('email', $credentials['email'])->whereRaw("BINARY `email_verification_code`= ?", [$credentials['verification_code']])->first();



        if ($exist->is_verified == 1) {

            return response(['message' => 'already verified'], 302);
        }

        try {

            Merchant::where('email', $credentials['email'])->where('email_verification_code', $credentials['verification_code'])->update([

                'is_verified' => 1

            ]);

            return response(['message' => 'accepted'], 201);
        } catch (Exception $e) {

            Log::error($e);

            return response(['message' => 'not accepted'], 406);
        }
    }
    //resend verification
    public function resendVerification($credentials)
    {

        $merchant = Merchant::where('email', $credentials['email'])->first();

        if ($merchant->is_verified == 1) {

            return response(['message' => 'already verified'], 302);
        }

        $verification = Str::random(6);

        $merchant->email_verification_code = $verification;

        $merchant->update();

        $data = ['verification_code' => $verification, 'name' => $merchant->full_name];

        Mail::send('Email Verification', $data, $credentials['email'], 'mail.verification');

        return response(['message' => 'success'], 201);
    }
    // Merchant log in authentication 
    public function attempt($credentials)
    {

        $exist = Merchant::where('email', $credentials['email'])->first();

        if (!$exist) {

            return response(['message' => 'not found'], 404);
        }

        if ($exist->is_verified == 0) {

            return response(['message' => 'not acceptable'], 406);
        }



        $match = Hash::check($credentials['password'], $exist->password);



        if ($match) {

            $user_name = $exist->user_name;

            $uuid = $exist->uuid;

            $phone = $exist->phone;

            $email = $exist->email;

            $os = Agent::platform();

            $browser = Agent::browser();

            $macAddress = exec('getmac');

            $ipAddress = request()->ip();

            $expDate = Carbon::now()->addDays(30);

            $tokenData = [

                'uuid' => $uuid,

                'phone' => $phone,

                'email' => $email,

                'user_name' => $user_name,

                'os' => $os,

                'browser' => $browser,

                'mac_address' => $macAddress,

                'exp_date' => $expDate

            ];



            $token = Token::create($tokenData);

            if ($token) {

                $exist_login = MerchantAccessToken::where('os', $os)->where('browser', $browser)->where('mac_address', $macAddress)->first();

                if ($exist_login) {

                    $exist_login->token = $token;

                    $exist_login->update();

                    return response(['token' => $token], 201);
                }

                $result = MerchantAccessToken::create([

                    'merchant_uuid' => $uuid,

                    'token' => $token,

                    'os' => $os, // Agent::platform()

                    'browser' => $browser, //Agent::browser()

                    'mac_address' => $macAddress, //exec('getmac')

                    'ip_address' =>  $ipAddress, // request()->ip()

                ]);



                if ($result) {

                    return response(['token' => $token], 201);
                }
            }
        }

        return response(['message' => 'not acceptable'], 406);
    }
    // forget password 
    public function forgetPassword($credentials)
    {

        $merchant = Merchant::where('email', $credentials['email'])->first();



        $verification_code = Str::random(6);

        $data = ['verification_code' => $verification_code];

        Mail::send('Email Verification', $data, $credentials['email'], 'mail.forgetpassword');

        $exists = ResetPassword::where('email', $credentials['email'])->where('uuid', $merchant->uuid)->first();

        if ($exists) {

            $result = ResetPassword::where('email', $credentials['email'])->where('uuid', $merchant->uuid)->update(['verification_code' => $verification_code]);
        } else {

            $result = ResetPassword::create([

                'uuid' => $merchant->uuid,

                'email' => $merchant->email,

                'verification_code' => $verification_code

            ]);
        }



        if ($result) {

            return response(['message' => 'success'], 201);
        }

        return response(['message' => 'not found'], 404);
    }
    //reset password
    public function resetPassword($credentials)
    {

        $user_info = ResetPassword::where('verification_code', $credentials['verification_code'])->first();

        $new_password = hash::make($credentials['password']);

        $result = Merchant::where('uuid', $user_info->uuid)->where('email', $user_info->email)->update([

            'password' => $new_password,

        ]);

        if ($result) {

            ResetPassword::where('verification_code', $credentials['verification_code'])->delete();

            return response(['message' => 'success'], 202);
        }

        return response(['message' => 'user not found'], 404);
    }
    //change password 
    public function changePassword($credentials, $header)
    {

        $token = Token::decode($header);

        $uuid = $token['uuid'];

        $exist = Merchant::where('uuid', $uuid)->where('email', $credentials['email'])->first();

        $result = Hash::check($credentials['oldPassword'], $exist->password);

        if ($result) {

            $updatePassword = Merchant::where('email', $credentials['email'])->update([

                'password' =>  Hash::make($credentials['newPassword'])

            ]);

            if ($updatePassword) {

                return response(['message' => 'success'], 200);
            }
        }

        return response(['message' => 'not acceptable'], 406);
    }
    public function profile($token, $file)
    {

        $token = Token::decode($token);

        $file = $file;

        $uuid = $token['uuid'];

        $exist = MerchantProfile::where('merchant_uuid', $uuid)->first();

        $path = FileSystem::storeFile($file, 'profile/images');



        if ($exist) {

            FileSystem::deleteFile($exist->path);

            $exist->path = $path;

            $result = $exist->update();
        } else {

            $result = MerchantProfile::create([

                'merchant_uuid' => $uuid,

                'path' => $path,

            ]);
        }



        if ($result) {

            return response(['message' => 'success'], 201);
        }

        return response(['message' => 'not accepted'], 406);
    }
    public function featured($merchant_uuid)
    {

        $featured = MerchantInfo::where('merchant_uuid', $merchant_uuid)->first();

        if ($featured) {

            try {

                $result = MerchantInfo::where('merchant_uuid', $merchant_uuid)->update([

                    'featured' => 0,

                ]);
            } catch (Exception $e) {

                Log::error($e);

                $result = false;
            }
        } else {

            try {

                $result = MerchantInfo::where('merchant_uuid', $merchant_uuid)->update([

                    'featured' => 1,

                ]);
            } catch (Exception $e) {

                Log::error($e);

                $result = false;
            }
        }

        if ($result) {

            return response(['message' => 'success'], 202);
        }

        return response(['message' => 'not acceptable'], 406);
    }
    public function getMerchantInfo($uuid)
    {
        return Merchant::where('uuid', $uuid)->with('info')->first();
    }
    public function getAllPosts($uuid)
    {
        return Post::with(

            'author.info:merchant_uuid,company_logo',

            'product:id,uuid,name',

            'product.details:product_uuid,price,cover,stock',

            'product.details.cover',

            'gallery',

            'like',

            'comment.userInfo:user_uuid,user_name',

            'comment.profile:user_uuid,path',

            'comment.reply.userInfo:user_uuid,user_name',

            'comment.reply.profile:user_uuid,path',

        )->where('merchant_uuid', $uuid)->paginate(30);
    }
 // get all products of merchant by token
    public function getAllProductsByToken($token)
    {
        $tokenData = Token::decode($token);
        return Product::where('merchant_uuid', $tokenData['uuid'])->select('name', 'uuid')->with('details:product_uuid,price,cover,stock', 'details.cover')->orderBy('id', 'DESC')->paginate(10);
        
    }
 // get all products gallery of merchant by token
    public function getAllProductsGalleryByToken($token)
    {
        $tokenData = Token::decode($token);
        return ProductGallery::where('merchant_uuid',$tokenData['uuid'])->orderBy('id', 'DESC')->paginate(10);
    }
    // get all products of merchant uuid
    public function getAllProducts($uuid)
    {
    return Product::where('merchant_uuid', $uuid)->select('name', 'uuid')->with('details:product_uuid,price,cover,stock', 'details.cover')->orderBy('id', 'DESC')->paginate(30);
         
        
    }
}