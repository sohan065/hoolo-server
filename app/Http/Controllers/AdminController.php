<?php

namespace App\Http\Controllers;

use Token;
use Exception;
use FileSystem;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Merchant;
use App\Models\CourseOrder;
use App\Models\Product;
use App\Models\ProductOrderDetail;
use App\Models\Brand;
use App\Models\DpCategory;
use App\Models\Category;
use App\Models\PCategory;
use App\Models\GCategory;
use Illuminate\Http\Request;
use App\Models\ProductOrder;
use App\Models\MerchantInfo;
use App\Models\MerchantAccessToken;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //  category status change
    public function childCategoryStatus(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:categories,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        
        
         $categoryInfo=Category::where('uuid',$validated['uuid'])->first();
        
        $status= $categoryInfo->is_active;
        
        $categoryInfo->is_active=!$status;
        $categoryInfo->update();
       return response(['message'=>'updated'],202);
        
    }
    // grand category status change
    public function grandCategoryStatus(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:g_categories,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        
         $categoryInfo=GCategory::where('uuid',$validated['uuid'])->first();
        
        $status= $categoryInfo->is_active;
        
        $categoryInfo->is_active=!$status;
        $categoryInfo->update();
       return response(['message'=>'updated'],202);
        
    }
    // parent category status change
    public function parentCategoryStatus(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:p_categories,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        
         $categoryInfo=PCategory::where('uuid',$validated['uuid'])->first();
        
        $status= $categoryInfo->is_active;
        
        $categoryInfo->is_active=!$status;
        $categoryInfo->update();
       return response(['message'=>'updated'],202);
        
    }
    // dp category status change
    public function dpcategoryCategoryStatus(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:dp_categories,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        
         $categoryInfo=DpCategory::where('uuid',$validated['uuid'])->first();
        
        $status= $categoryInfo->is_active;
        
        $categoryInfo->is_active=!$status;
        $categoryInfo->update();
       return response(['message'=>'updated'],202);
        
    }
    // get all course order list
    public function getAllCourseOrderList(){
        
        return CourseOrder::with('user','course','course.details','course.details.cover')->get();
    }
    public function adminLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email',
            'password' => 'bail|required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        
        $email = $request->email;
        $pass = $request->password;
        if($email == env('ADMIN_EMAIL') && $pass == env('ADMIN_PASSWORD')){
            $tokenData = [
                'uuid' => Str::uuid(),
                'email' => $email,
                'password'=>$pass
            ];
            return $token = Token::create($tokenData);
        }
        
        $response = ['email' => array('invalid email'),'password'=>array('invalid password')];
        return response($response, 422);
    }
    // get all product order list 
    public function getAllProductOrder()
    {
        return ProductOrderDetail::with(
            'payment',
            'order.product:uuid,name',
            'order.product.details:product_uuid,cover',
            'order.product.details.cover',
            'country',
            'state:uuid,name',
            'city:uuid,name',
            'thana:uuid,name',
            'postCode:uuid,name'
        )->orderBy('id', 'desc')->paginate(10);
    }
     // get all confirm product order list
    public function  getConfirmOrders()
    {
        return ProductOrderDetail::where('order_status', 1)->with(
            'payment',
            'order.product:uuid,name',
            'order.product.details:product_uuid,cover',
            'order.product.details.cover',
            'country',
            'state:uuid,name',
            'city:uuid,name',
            'thana:uuid,name',
            'postCode:uuid,name'
        )->orderBy('id', 'desc')->paginate(10);
    }
    // get all pending product order list
    public function  getPendingOrders()
    {
        return ProductOrderDetail::where('order_status', 0)->with(
            'payment',
            'order.product:uuid,name',
            'order.product.details:product_uuid,cover',
            'order.product.details.cover',
            'country',
            'state:uuid,name',
            'city:uuid,name',
            'thana:uuid,name',
            'postCode:uuid,name'
        )->orderBy('id', 'desc')->paginate(10);
    }
    // get all cancel product order list
    public function  getCancelOrders()
    {
        return ProductOrderDetail::where('order_status', 2)->with(
            'payment',
            'order.product:uuid,name',
            'order.product.details:product_uuid,cover',
            'order.product.details.cover',
            'country',
            'state:uuid,name',
            'city:uuid,name',
            'thana:uuid,name',
            'postCode:uuid,name'
        )->orderBy('id', 'desc')->paginate(10);
    }
    // get all shipped product order list
    public function  getShippedOrders()
    {
        return ProductOrderDetail::where('delivery_status', 3)->with(
            'payment',
            'order.product:uuid,name',
            'order.product.details:product_uuid,cover',
            'order.product.details.cover',
            'country',
            'state:uuid,name',
            'city:uuid,name',
            'thana:uuid,name',
            'postCode:uuid,name'
        )->orderBy('id', 'desc')->paginate(10);
    }
     // get all brand 
    public function getAllBrand()
    {
        return Brand::orderBy('id', 'DESC')->paginate(10);
    }
     // get all grand category
    public function getAllGcategory()
    {
        return GCategory::orderBy('id', 'DESC')->paginate(10);
    }
    // get all parent category
    public function getAllPcategory()
    {
        return PCategory::orderBy('id', 'DESC')->paginate(10);
    }
    // get all  category
    public function getAllCategory()
    {
        return Category::orderBy('id', 'DESC')->paginate(10);
    }
    // get all dp category
    public function getAllDpCategory()
    {
        return DpCategory::orderBy('id', 'DESC')->paginate(10);
    }
    public function getAllInstructor()
    {
        return User::where('type', 1)->with('instructor', 'instructorDetail', 'userInfo.profile')->orderBy('id', 'DESC')->paginate(10);
    }
    public function getAllMerchant()
    {
         return Merchant::with('info.country','info.state','info.city','info.thana', 'info.postcode','profile')->orderBy('id', 'DESC')->paginate(10);
    }
     public function merchantLogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:merchants,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);

        $exist = Merchant::where('uuid', $validated)->first();
        if ($exist) {

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
             Token::decode($token);
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
        return response(['message' => 'not found'], 404);
    }
    // update merchant info
    public function merchantUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|exists:merchants,uuid',
            'full_name' => 'bail|required|string|min:5',
            'phone' => 'bail|required|string|min:8|max:15',
            'email' => 'bail|required|email',
            'user_name' => 'bail|required|string|min:3',
            'country_uuid' => 'bail|required|string|exists:countries,uuid',
            'state_uuid' => 'bail|required|string|exists:states,uuid',
            'city_uuid' => 'bail|required|string|exists:cities,uuid',
            'thana_uuid' => 'bail|nullable|string|exists:thanas,uuid',
            'post_code_uuid' => 'bail|required|string|exists:post_codes,uuid',
            'about' => 'bail|required|string|min:20',
            'company_name' => 'bail|required|string|min:2',
            'company_logo' => 'bail|nullable|image',
            'company_banner' => 'bail|nullable|image',
            'website' => 'bail|nullable|string|min:5',
        ]);
       
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid', 'full_name', 'phone', 'email', 'user_name', 'password', 'country_uuid', 'state_uuid', 'city_uuid', 'thana_uuid', 'post_code_uuid', 'about', 'company_name', 'website']);
        $company_logo = $request->file('company_logo');
        $company_banner = $request->file('company_banner');

        $updateMerchant = $this->updateMerchant($validated);
        if ($updateMerchant) {
            $updateMerchantInfo = $this->updateMerchantInfo($validated, $company_logo, $company_banner);

            if ($updateMerchantInfo) {
                                $merchant = Merchant::where('uuid',$validated['uuid'])->with('info.country','info.state','info.city','info.thana', 'info.postcode','profile')->first();

                return response($merchant, 202);
            }
        }
        return response(['message' => 'not acceptable'], 406);
    }
    // update merchant
    protected function updateMerchant($credentials)
    {
        $exists = Merchant::where('uuid', $credentials['uuid'])->first();
        $email = $credentials['email'];
        if (!$credentials['email'] == $exists->email) {
            $duplicate =  Merchant::where('email', $credentials['email'])->get();
            if ($duplicate) {
                $response = ['email' => array('email already exists')];
                return response($response, 422);
            }
        }

        try {
            $result = Merchant::where('uuid', $credentials['uuid'])->update([
                'full_name' => $credentials['full_name'],
                'phone' =>  $credentials['phone'],
                'email' =>  $email,
                'user_name' => $credentials['user_name'],
            ]);
        } catch (Exception $e) {

            Log::error($e);
            return $e;

            $result = false;
        }

        return $result;
    }
    // update merchant info 
    protected function updateMerchantInfo($credentials, $companyLogo, $companyBanner)
    {
        $merchantInfo = MerchantInfo::where('merchant_uuid', $credentials['uuid'])->first();
        $website = null;
        if (array_key_exists('website', $credentials)) {

            $website = $credentials['website'];
        }
        $banner = null;
        if ($companyBanner) {
            $old_banner_path = $merchantInfo['company_banner'];
            $deleteBanner = FileSystem::deleteFile($old_banner_path);
            $banner = FileSystem::storeFile($companyBanner, 'stores/banner');
        }
        $logo = null;
        if ($companyLogo) {
            $old_logo_path = $merchantInfo['logo'];
            $deleteLogo = FileSystem::deleteFile($old_logo_path);
            $logo = FileSystem::storeFile($companyLogo, 'stores/logo');
        }
        try {
            $result = MerchantInfo::where('merchant_uuid', $credentials['uuid'])->update([
                'country_uuid' => $credentials['country_uuid'],

                'state_uuid' => $credentials['state_uuid'],

                'city_uuid' => $credentials['city_uuid'],

                'thana_uuid' => $credentials['thana_uuid'],

                'post_code_uuid' => $credentials['post_code_uuid'],

                'about' => $credentials['about'],

                'company_name' => $credentials['company_name'],

                'company_logo' => $logo == null ? $merchantInfo['company_logo'] : $logo,

                'company_banner' => $banner == null ? $merchantInfo['company_banner']: $banner,

                'website' => $website,

            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }

        return $result;
    }
    public function getProductList()
    {
        return Product::with('merchant:uuid,full_name', 'details:product_uuid,price,cover,stock', 'details.cover')->orderBy('id', 'DESC')->paginate(10);
    }
     // update grand category
    public function updateGcategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3',
            'uuid' => 'bail|required|string|exists:g_categories,uuid',
            'icon' => 'bail|nullable|image',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $icon = $request->file('icon');
        $credentils = $request->only(['name', 'uuid']);
        $existData = GCategory::where('uuid', $credentils['uuid'])->first();
        if ($credentils['name'] == $existData->name) {
            $temp  = true;
        } else {
            $exist = GCategory::where('name', $credentils['name'])->first();
            if ($exist) {
                $response = ['name' => array('name already exists')];
                return response($response, 422);
            }
            $temp = false;
        }
        if ($icon) {
            $deleteOldIcon = Filesystem::deleteFile($existData['icon']);
            $icon = Filesystem::storeFile($icon, 'gcategory/icons');
        }
        try {
            $result = GCategory::where('uuid', $credentils['uuid'])->update([
                'name' => $temp ? $existData['name'] : $credentils['name'],
                'icon' => $icon ? $icon : $existData['icon'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
            $result=GCategory::where('uuid', $credentils['uuid'])->first();
            return response( $result, 202);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    // update parent category
    public function updatePcategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3',
            'uuid' => 'bail|required|string|exists:p_categories,uuid',
            'icon' => 'bail|nullable|image',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $icon = $request->file('icon');
        $credentils = $request->only(['name', 'uuid']);

        $existData = PCategory::where('uuid', $credentils['uuid'])->first();
        if ($credentils['name'] == $existData->name) {
            $temp  = true;
        } else {
            $exist = PCategory::where('name', $credentils['name'])->first();
            if ($exist) {
                $response = ['name' => array('name already exists')];
                return response($response, 422);
            }
            $temp = false;
        }
        if ($icon) {
            $deleteOldIcon = Filesystem::deleteFile($existData['icon']);
            $icon = Filesystem::storeFile($icon, 'pcategory/icons');
        }
        try {
            $result = PCategory::where('uuid', $credentils['uuid'])->update([
                'name' => $temp ? $existData['name'] : $credentils['name'],
                'icon' => $icon ? $icon : $existData['icon'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
             $result=PCategory::where('uuid', $credentils['uuid'])->first();
            return response( $result, 202);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    // update child category
    public function updateCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3',
            'uuid' => 'bail|required|string|exists:categories,uuid',
            'icon' => 'bail|nullable|image',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $icon = $request->file('icon');
        $credentils = $request->only(['name', 'uuid']);
        $existData = Category::where('uuid', $credentils['uuid'])->first();
        if ($credentils['name'] == $existData->name) {
            $temp  = true;
        } else {
            $exist = Category::where('name', $credentils['name'])->first();
            if ($exist) {
                $response = ['name' => array('name already exists')];
                return response($response, 422);
            }
            $temp = false;
        }
        if ($icon) {
            $deleteOldIcon = Filesystem::deleteFile($existData['icon']);
            $icon = Filesystem::storeFile($icon, 'category/icons');
        }
        try {
            $result = Category::where('uuid', $credentils['uuid'])->update([
                'name' => $temp ? $existData['name'] : $credentils['name'],
                'icon' => $icon ? $icon : $existData['icon'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
           $result=Category::where('uuid', $credentils['uuid'])->first();
            return response( $result, 202);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    // update dp category
    public function updateDpCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3',
            'uuid' => 'bail|required|string|exists:dp_categories,uuid',
            'icon' => 'bail|nullable|image',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $icon = $request->file('icon');
        $credentils = $request->only(['name', 'uuid']);
        $existData = DpCategory::where('uuid', $credentils['uuid'])->first();
        if ($credentils['name'] == $existData->name) {
            $temp  = true;
        } else {
            $exist = DpCategory::where('name', $credentils['name'])->first();
            if ($exist) {
                $response = ['name' => array('name already exists')];
                return response($response, 422);
            }
            $temp = false;
        }
        if ($icon) {
            $deleteOldIcon = Filesystem::deleteFile($existData['icon']);
            $icon = Filesystem::storeFile($icon, 'dpcategory/icon');
        }
        try {
            $result = DpCategory::where('uuid', $credentils['uuid'])->update([
                'name' => $temp ? $existData['name'] : $credentils['name'],
                'icon' => $icon ? $icon : $existData['icon'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
           $result=DpCategory::where('uuid', $credentils['uuid'])->first();
            return response( $result, 202);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    // update brand
    public function updateBrand(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3',
            'uuid' => 'bail|required|string|exists:brands,uuid',
            'icon' => 'bail|nullable|image',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $icon = $request->file('icon');
        $credentils = $request->only(['name', 'uuid']);
        $existData = Brand::where('uuid', $credentils['uuid'])->first();
        if ($credentils['name'] == $existData->name) {
            $temp  = true;
        } else {
            $exist = Brand::where('name', $credentils['name'])->first();
            if ($exist) {
                $response = ['name' => array('name already exists')];
                return response($response, 422);
            }
            $temp = false;
        }
        if ($icon) {
            $deleteOldIcon = Filesystem::deleteFile($existData['icon']);
            $icon = Filesystem::storeFile($icon, 'dpcategory/icon');
        }
        try {
            $result = Brand::where('uuid', $credentils['uuid'])->update([
                'name' => $temp ? $existData['name'] : $credentils['name'],
                'icon' => $icon ? $icon : $existData['icon'],
            ]);
        } catch (Exception $e) {
            Log::error($e);
            $result = false;
        }
        if ($result) {
           $result=Brand::where('uuid', $credentils['uuid'])->first();
            return response( $result, 202);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    
    
}