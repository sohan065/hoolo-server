<?php



namespace App\Http\Controllers;



use Validator;

use Instructor;

use Illuminate\Http\Request;

use App\Models\IntstructorDetail;



class InstructorController extends Controller

{
    public function getInstructorByUuid(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|exists:users,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only('uuid');
        return Instructor::getInstructorByUuid($validated['uuid']);
    }

    public function getAllInstructor()

    {

        return Instructor::getAllInstructor();

    }

    // instructor registration

    public function instructorReg(Request $request)

    {



        $validator = Validator::make($request->all(), [

            'phone' => 'bail|required|string|min:11|unique:users,phone',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);

        }

        $validated = $request->only('phone');



        return Instructor::instructorReg($validated);

    }

    // instructor phone otp verification 

    public function otpVerify(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'phone' => 'bail|required|string|exists:users,phone',

            'otp' => 'bail|required|string|exists:users,otp',

            'mac_address' => 'bail|required|string',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);

        }

        $validated = $request->only('phone', 'otp', 'mac_address');



        return Instructor::otpVerify($validated);

    }

    // instructor infos

    public function createInstructorInfo(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'full_name' => 'bail|required|string|min:5',

            'user_name' => 'bail|required|string|min:3',

            'about_me' => 'bail|required|string|min:3',

            'media_name' => 'bail|required|string|min:3',

            'media_link' => 'bail|required|string|min:3',

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

        $validated = $request->only(['full_name', 'user_name', 'about_me', 'country_uuid', 'state_uuid', 'city_uuid', 'thana_uuid', 'post_code_uuid', 'media_name', 'media_link']);

        return Instructor::createInstructorInfo($validated, $token);

    }

    // instructor details 

    public function createInstructorDetail(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'dp_category_uuid' => 'bail|required|string|exists:dp_categories,uuid',

            'frequency' => 'bail|required|numeric',

            'class_type' => 'bail|required|string',

            'area_of_expertice' => 'bail|required|string',

            'certification' => 'bail|required|array|min:1',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);

        }

        $token = $request->header('token');

        $cretification = $request->file('certification');

        $validated = $request->only(['dp_category_uuid', 'frequency', 'class_type', 'area_of_expertice', 'certification']);

        return Instructor::createInstructorDetail($validated, $cretification, $token);

    }

    // instructor resend otp code 

    public function resendOtpCode(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'phone' => 'bail|required|string|exists:users,phone',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);

        }

        $validated = $request->only(['phone']);

        return Instructor::resendOtpCode($validated);

    }

    // featured instructor store

    public function storeFeatured(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'instructor_uuid' => 'bail|required|string|unique:featured_instructors,instructor_uuid|exists:users,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);

        }

        $validated = $request->only(['instructor_uuid']);

        return Instructor::storeFeatured($validated);

    }

    public function getAllFeaturedInstructor()

    {

        return Instructor::getAllFeaturedInstructor();

    }

    // featured instructor delete

    public function deleteFeatured(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|min:3|exists:featured_instructors,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);

        }

        $validated = $request->only(['uuid']);

        return Instructor::deleteFeatured($validated);

    }

}

