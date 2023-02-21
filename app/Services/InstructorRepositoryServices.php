<?php



namespace App\Services;





use Sms;

use Token;

use Exception;

use FileSystem;

use Carbon\Carbon;

use App\Models\User;

use Illuminate\Support\Str;

use App\Models\InstructorInfo;

use App\Models\UserAccessToken;

use App\Models\IntstructorDetail;

use App\Models\FeaturedInstructor;

use Illuminate\Support\Facades\Log;

use Jenssegers\Agent\Facades\Agent;

use App\Repositories\InstructorRepositoryInterface;



class InstructorRepositoryServices implements InstructorRepositoryInterface

{
    public function getInstructorByUuid($uuid)
    {
        return InstructorInfo::where('instructor_uuid', $uuid)->with('profile')->first(); 
    }

    public function getAllInstructor()

    {
        return User::where('type', 1)->with('instructor', 'profile')->orderBy('id', 'DESC')->get();

        // return User::where('type', 1)->with('instructor:instructor_uuid,user_name')->get();

    }

    // instructor registration

    public function instructorReg($credentials)

    {

        try {

            $otp = rand(1111, 9999);

            $result = User::create([

                'uuid' => Str::uuid(),

                'phone' => $credentials['phone'],

                'type' => 1,

                'otp' => $otp,

            ]);

        } catch (Exception $e) {

            Log::error($e);

            return $e;

            $result = false;

        }

        if ($result) {

            $array = ['contacts' => $credentials['phone'], 'msg' => 'Your OTP CODE for hoolo.live is ' . $otp];

            Sms::sendSms($array);

            return response(['message' => 'created'], 201);

        }

        return response(['message' => 'not acceptable'], 406);

    }

    // instructor phone otp verification 

    public function otpVerify($credentials)

    {

        $user = User::where('phone', $credentials['phone'])->where('otp', $credentials['otp'])->first();

        try {

            $update =  User::where('phone', $credentials['phone'])->where('otp', $credentials['otp'])->update([

                'otp' => null,

            ]);

            if ($update) {

                $os = Agent::platform();

                $browser = Agent::browser();

                $macAddress = $credentials['mac_address'];

                $ipAddress = request()->ip();

                $user_uuid = $user['uuid'];

                $type = $user['type'];

                $phone = $user['phone'];

                $tokenData = [

                    'uuid' => $user_uuid,

                    'phone' => $phone,

                    'os' => $os,

                    'browser' => $browser,

                    'mac_address' => $macAddress,

                    'ip_address' =>  $ipAddress,

                    'type' => $type,

                    'time' => Carbon::now()

                ];

                $token = Token::create($tokenData);

                if ($token) {

                    try {

                        $result = UserAccessToken::updateOrCreate(

                            ['user_uuid' => $user_uuid, 'os' => $os, 'browser' => $browser, 'mac_address' => $macAddress],

                            ['token' => $token, 'ip_address' => $ipAddress]

                        );

                    } catch (Exception $e) {

                        log::error($e);

                        $result = false;

                    }

                    if ($result) {

                        return response(['token' => $token], 202);

                    }

                }

            }

        } catch (Exception $e) {

            Log::error($e);

            return response(['message' => 'not found'], 404);

        }

    }

    // instructor resend otp code 

    public function resendOtpCode($credentials)

    {

        $otp = rand(1111, 9999);

        try {

            $result = User::where('phone', $credentials['phone'])->update([

                'otp' => $otp,

            ]);

        } catch (Exception $e) {

            log::error($e);

            $result = false;

        }

        if ($result) {

            $array = ['contacts' => $credentials['phone'], 'msg' => 'Your OTP CODE for hoolo.live is ' . $otp];

            Sms::sendSms($array);

            return response(['message' => 'success'], 201);

        }

        return response(['message' => 'not acceptable'], 406);

    }

    // instructor infos

    public function createInstructorInfo($credentials, $token)

    {

        $tokenInfo = Token::decode($token);

        try {

            $result = InstructorInfo::create([

                'uuid' => Str::uuid(),

                'instructor_uuid' => $tokenInfo['uuid'],

                'full_name' => $credentials['full_name'],

                'user_name' => $credentials['user_name'],

                'about_me' => $credentials['about_me'],

                'media_name' => $credentials['media_name'],

                'media_link' => $credentials['media_link'],

                'country_uuid' => $credentials['country_uuid'],

                'state_uuid' => $credentials['state_uuid'],

                'city_uuid' => $credentials['city_uuid'],

                'thana_uuid' => $credentials['thana_uuid'],

                'post_code_uuid' => $credentials['post_code_uuid'],

            ]);

        } catch (Exception $e) {

            log::error($e);

            $result = false;

        }

        if ($result) {

            return response(['message' => 'created'], 201);

        }

        return response(['message' => 'not acceptable'], 406);

    }

    //instructor details

    public function createInstructorDetail($credentials, $certification, $token)

    {

        $validFile = true;

        foreach ($certification as $key => $file) {

            if ($file->extension() != 'pdf' && $file->extension() != 'jpg' && $file->extension() != 'jpeg' && $file->extension() != 'png') {

                $validFile = false;

            }

        }

        if (!$validFile) {

            $response = ['certification' => array('file extension must be pdf or jpg or jpeg or png ')];

            return response($response, 422);

        }

        $fileList = array();

        foreach ($certification as $key => $file) {

            $path = FileSystem::storeFile($file, 'instructor/certifications');

            if ($path) {

                array_push($fileList, $path);

            }

        }

        try {

            $tokenInfo = Token::decode($token);

            $result = IntstructorDetail::create([

                'uuid' => Str::uuid(),

                'instructor_uuid' => $tokenInfo['uuid'],

                'dp_category_uuid' => $credentials['dp_category_uuid'],

                'frequency' => $credentials['frequency'],

                'class_type' => $credentials['class_type'],

                'area_of_expertice' => $credentials['area_of_expertice'],

                'certification' => json_encode($fileList),

            ]);

        } catch (Exception $e) {

            Log::error($e);

            $result = false;

        }

        if ($result) {

            return response(['result' => $result], 201);

        }

        foreach ($fileList as $key => $file) {

            $path = $file;

            FileSystem::deleteFile($path);

        }

        return response(['message' => 'not acceptable'], 406);

    }

    // store featured instructor 

    public function storeFeatured($credentils)

    {

        try {

            $result = FeaturedInstructor::create([

                'uuid' => Str::uuid(),

                'instructor_uuid' => $credentils['instructor_uuid'],

            ]);

        } catch (Exception $e) {

            Log::error($e);

            $result = false;

        }

        if ($result) {

            return response(['message' => 'created'], 201);

        }

        return response(['message' => 'not acceptable'], 406);

    }

    public function getAllFeaturedInstructor()

    {

        return FeaturedInstructor::with('instructor:instructor_uuid,user_name', 'profile:user_uuid,path')->orderBy('id', 'DESC')->get();

    }

    // delete featured instructor 

    public  function deleteFeatured($credentils)

    {

        try {

            $result = FeaturedInstructor::where('uuid', $credentils['uuid'])->delete();

        } catch (Exception $e) {

            log::error($e);

            $result = false;

        }

        if ($result) {

            return response(['message' => 'deleted'], 410);

        }

        return response(['message' => 'not acceptable'], 406);

    }

}

