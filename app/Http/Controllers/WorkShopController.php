<?php

namespace App\Http\Controllers;

use SSL;
use Bkash;
use WorkShop;
use Validator;
use Illuminate\Http\Request;

class WorkShopController extends Controller
{
    public function getAllWorkshop()
    {

        return WorkShop::getAllWorkshop();
    }
    // get all workshop by instructor\user  uuid
    public function getWorkshopByInstructor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:users,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['uuid']);
        return WorkShop::getCourseByInstructor($validated['uuid']);
    }
    // get course details by workshop uuid
    public function getWorkshopDetails(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:workshops,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return WorkShop::getWorkshopDetails($validated);
    }
    // get all user course
    public function getAllUserCourse(Request $request)
    {

        $token = $request->header('token');

        return WorkShop::getAllUserCourse($token);
    }
    // workshop payment by CARD
    public function orderWorkshopByCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:workshops,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $token = $request->header('token');
        $validated = $request->only(['uuid']);
        return WorkShop::orderWorkshopByCard($validated, $token);
    }
    // course order CARD payment success 
    public function workshopCardSuccess(Request $request)
    {
        $credentials = $request->all();
        return SSL::workshopCardSuccess($credentials);
    }
    // course order CARD payment fail 
    public function workshopCardFail(Request $request)
    {
        $credentials = $request->all();
        return SSL::workshopCardFail($credentials);
    }
    // course order CARD payment cancel 
    public function workshopCardCancel(Request $request)
    {
        $credentials = $request->all();
        return SSL::workshopCardCancel($credentials);
    }
    // course order CARD payment refund 
    public function workshopCardRefund(Request $request)
    {
        $credentials = $request->all();
        return SSL::productCardRefund($credentials);
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
        return WorkShop::orderCourseByBkash($validated, $token);
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
    // workshop booking process
    public function bookingWorkshop(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:workshops,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $token = $request->header('token');
        $validated = $request->only(['uuid']);
        return WorkShop::bookingWorkshop($validated, $token);
    }
    public function saveWorkshop(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'dp_category_uuid' => 'bail|required|string|exists:dp_categories,uuid',

            'cover_uuid' => 'bail|required|string|exists:workshop_galleries,uuid',

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

        $validated = $request->only(['dp_category_uuid', 'title', 'price', 'discount', 'discount_duration', 'discount_type', 'language', 'level', 'schedule', 'details', 'type', 'session_title', 'summary', 'cover_uuid', 'slot']);

        return WorkShop::saveWorkshop($validated, $token);
    }
    public function createWorkshopGallery(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'file' => 'bail|required|mimes:jpeg,jpg,png,mp4,mov,mkv,3gp',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $token = $request->header('token');

        $file = $request->file('file');

        return WorkShop::createWorkshopGallery($token, $file);
    }
    public function deleteWorkshopGallery(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:workshop_galleries,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $token = $request->header('token');

        $validated = $request->only(['uuid']);

        return WorkShop::deleteWorkshopGallery($validated, $token);
    }
    // featured workshop store
    public function storeFeaturedWorkshop(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'workshop_uuid' => 'bail|required|string|unique:featured_workshops,workshop_uuid|exists:workshops,uuid',

        ]);


        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['workshop_uuid']);

        return WorkShop::storeFeaturedWorkshop($validated);
    }
    public function getAllFeaturedWorkshop()
    {

        return WorkShop::getAllFeaturedWorkshop();
    }
    // featured workshop delete
    public function deleteFeaturedWorkshop(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|min:3|exists:featured_workshops,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return WorkShop::deleteFeaturedWorkshop($validated);
    }
}
