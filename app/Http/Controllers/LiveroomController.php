<?php

namespace App\Http\Controllers;

use Validator;
use LiveRoom;
use App\Models\Course;
use Illuminate\Http\Request;

class LiveroomController extends Controller
{
    function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'creator_uuid' => 'bail|required|string|exists:users,uuid',
            'course_uuid' => 'bail|required|string|exists:courses,uuid',
            'room_id' => 'bail|required|string|unique:live_rooms',
            'type' => 'bail|required|numeric|min:0|max:1',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['creator_uuid', 'room_id', 'course_uuid', 'type']);
        return LiveRoom::store($validated);
    }

    function creatorVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'bail|required|string|exists:users,phone|max:17',
            'country_code' => 'bail|required|string',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['phone', 'country_code']);
        return LiveRoom::creatorVerification($validated);
    }
    function creatorVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'bail|required|string|exists:users,phone|max:17',
            'otp' => 'bail|required|string|exists:live_steraming_otps,otp|min:4|max:4',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['phone', 'otp']);
        return LiveRoom::creatorVerify($validated);
    }

    function phoneVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'bail|required|string|max:17',
            'country_code' => 'bail|required|string',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['phone', 'country_code']);
        return LiveRoom::phoneVerification($validated);
    }

    function phoneVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'bail|required|string|exists:live_steraming_otps,phone|max:17',
            'country_code' => 'bail|required|string',
            'otp' => 'bail|required|string|exists:live_steraming_otps,otp|min:4|max:4',
            'room_id' => 'bail|required|exists:live_rooms,room_id',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $validated = $request->only(['phone', 'otp', 'country_code', 'room_id']);
        return LiveRoom::phoneVerify($validated);
    }

    function courses($uuid)
    {
        return Course::where('creator_uuid', $uuid)->get();
    }
}
