<?php



namespace App\Http\Controllers;


use SSL;
use Bkash;
use Course;
use Validator;
use Illuminate\Http\Request;


class CourseController extends Controller

{

    public function getAllCourse()

    {

        return Course::getAllCourse();

    }

    public function getCourseByInstructor(Request $request){
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:users,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return Course::getCourseByInstructor($validated['uuid']);
    }
    
    public function getCourseByDpCategory(Request $request){
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:dp_categories,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return Course::getCourseByDpCategory($validated['uuid']);
    }

     public function getCourseDetails(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:courses,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);

        }

        $validated = $request->only(['uuid']);

        return Course::getCourseDetails($validated);

    }

      // get all user course

    public function getAllUserCourse(Request $request)
    {
        $token = $request->header('token');
        return Course::getAllUserCourse($token);
    }

       // course payment by CARD
    public function orderCourseByCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:courses,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $token = $request->header('token');
        $validated = $request->only(['uuid']);
        return Course::orderCourseByCard($validated, $token);
    }
    // course order CARD payment success 
    public function courseCardSuccess(Request $request)
    {
        $credentials = $request->all();
        return SSL::courseCardSuccess($credentials);
    }
    // course order CARD payment fail 
    public function courseCardFail(Request $request)
    {
        $credentials = $request->all();
        return SSL::courseCardFail($credentials);
    }
    // course order CARD payment cancel 
    public function courseCardCancel(Request $request)
    {
        $credentials = $request->all();
        return SSL::courseCardCancel($credentials);
    }
    // course order CARD payment refund 
    public function courseCardRefund(Request $request)
    {
        $credentials = $request->all();
        return SSL::courseCardRefund($credentials);
    }
    // course order payment process by Bkash 
    public function orderCourseByBkash(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:courses,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $token = $request->header('token');
        $validated = $request->only(['uuid']);
        return Course::orderCourseByBkash($validated, $token);
    }
    // course payment execute by Bkash
    public function orderExecute($paymentId)
    {
        return Bkash::coursePaymentExecute($paymentId);
    }
    //  Bkash course payment cancel
    public function orderCancel($paymentId)
    {
        return Bkash::coursePaymentCancel($paymentId);
    }
    // course booking process
    public function bookingCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:courses,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $token = $request->header('token');
        $validated = $request->only(['uuid']);
        return Course::bookingCourse($validated, $token);
    }

    public function saveCourse(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'dp_category_uuid' => 'bail|required|string|exists:dp_categories,uuid',

            'cover_uuid' => 'bail|required|string|exists:course_galleries,uuid',

            'title' => 'bail|required|string|min:3',

            'price' => 'bail|required|integer|min:0',

            'discount' => 'bail|nullable|numeric',

            'discount_duration' => 'bail|nullable|integer',

            'discount_type' => 'bail|nullable|integer',

            'language' => 'bail|required|string',

            'level' => 'bail|required|string',

            'summary' => 'bail|required|string|min:10',

            'session_title' => 'bail|required|array|min:1',

            'details' => 'bail|required|array|min:1',

            'type' => 'bail|required|boolean',

            'slot' => 'bail|required|numeric|min:1|max:50',

            'schedule' => 'bail|required|array|min:1',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);

        }

        $token = $request->header('token');

        $validated = $request->only(['dp_category_uuid', 'title', 'price', 'discount', 'discount_duration', 'discount_type', 'language', 'level', 'schedule', 'details','type', 'session_title', 'summary', 'cover_uuid', 'slot']);

        return Course::saveCourse($validated, $token);

    }



    public function createCourseGallery(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'file' => 'bail|required|mimes:jpeg,jpg,png,mp4,mov,mkv,3gp',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);

        }

        $token = $request->header('token');

        $file = $request->file('file');

        return Course::createCourseGallery($token, $file);

    }



    public function deleteCourseGallery(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:course_galleries,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);

        }

        $token = $request->header('token');

        $validated = $request->only(['uuid']);

        return Course::deleteCourseGallery($validated, $token);

    }

    // featured course store

    public function storeFeaturedCourse(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'course_uuid' => 'bail|required|string|unique:featured_courses,course_uuid|exists:courses,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);

        }

        $validated = $request->only(['course_uuid']);

        return Course::storeFeaturedCourse($validated);

    }

    public function getAllFeaturedCourse()

    {

        return Course::getAllFeaturedCourse();

    }

    // featured course delete

    public function deleteFeaturedCourse(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|min:3|exists:featured_courses,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);

        }

        $validated = $request->only(['uuid']);

        return Course::deleteFeaturedCourse($validated);

    }

}

