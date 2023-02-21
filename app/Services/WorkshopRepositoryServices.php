<?php

namespace App\Services;

use SSL;
use Bkash;
use Token;
use Invoice;
use Exception;
use FileSystem;
use App\Models\User;
use App\Models\WorkShop;
use App\Models\WorkshopDetail;
use App\Models\WorkshopGallery;
use App\Models\FeaturedWorkshop;
use App\Models\TempWorkshopOrder;
use App\Models\WorkshopOrder;
use App\Models\WorkshopSession;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Repositories\WorkshopRepositoryInterface;

class WorkshopRepositoryServices implements WorkshopRepositoryInterface
{
    public function getAllWorkshop()

    {

        return WorkShop::with('instructor:instructor_uuid,full_name,user_name', 'profile', 'dpCategory', 'details.cover', 'session')->paginate(30);
    }

    public function getWorkshopByInstructor($uuid)
    {
        return WorkShop::where('instructor_uuid', $uuid)->with('instructor:instructor_uuid,full_name,user_name', 'profile', 'dpCategory', 'details.cover', 'session')->paginate(30);
    }

    // get workshop  details by workshop-uuid

    public function getWorkshopDetails($credentials)

    {

        return WorkShop::where('uuid', $credentials['uuid'])->with('instructor:uuid,instructor_uuid,full_name,user_name,about_me', 'profile', 'details.cover', 'session')->withCount('order')->first();
    }

    // get  all user course 

    public function getAllUserCourse($token)

    {

        $tokenInfo = Token::decode($token);

        return CourseOrder::where('user_uuid', $tokenInfo['uuid'])->with('course.profile', 'course.dpCategory', 'course.details.cover', 'course.session', 'course.instructor:instructor_uuid,full_name,user_name')->get();
    }

    // workshop order by CARD
    public function orderWorkshopByCard($credentials, $token)
    {
        $tokenInfo = Token::decode($token);
        $exists = WorkshopOrder::where('workshop_uuid', $credentials['uuid'])->where('user_uuid', $tokenInfo['uuid'])->first();
        if ($exists) {
            return response(['message' => 'already found'], 302);
        }

        $userId = User::where('uuid', $tokenInfo['uuid'])->first()->id;
        $workshopInfo = WorkShop::where('uuid', $credentials['uuid'])->select('uuid', 'instructor_uuid', 'slot')->with('details:workshop_uuid,price', 'instructor:instructor_uuid,id')->first();
        $bookedSlot = WorkshopOrder::where('uuid', $credentials['uuid'])->count();
        if ($bookedSlot + 1 > $workshopInfo->slot) {
            return response(['message' => 'unavaiable slot'], 204);
        }
        $totalPrice = $workshopInfo->details->price;
        // $userId, $type
        $orderCode = Invoice::create($userId, 'course');
        try {
            $result = TempWorkshopOrder::create([
                'uuid' => Str::uuid(),
                'user_uuid' => $tokenInfo['uuid'],
                'workshop_uuid' => $workshopInfo->uuid,
                'payment_method' => 'CARD',
                'order_code' =>  $orderCode,
                'price' => $workshopInfo->details->price,
            ]);
        } catch (Exception $e) {
            log::error($e);
            $result = false;
        }
        if ($result) {
            return SSL::workshopOrderByCard($token, $totalPrice, $orderCode);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    // workshop order  by BKASH
    public function orderCourseByBkash($credentials, $token)
    {
        $tokenInfo = Token::decode($token);
        $exists = CourseOrder::where('course_uuid', $credentials['uuid'])->where('user_uuid', $tokenInfo['uuid'])->first();
        if ($exists) {
            return response(['message' => 'already found'], 302);
        }

        $userId = User::where('uuid', $tokenInfo['uuid'])->first()->id;
        $courseInfo = Course::where('uuid', $credentials['uuid'])->select('uuid', 'instructor_uuid', 'slot')->with('details:course_uuid,price', 'instructor:instructor_uuid,id')->first();
        $bookedSlot = CourseOrder::where('uuid', $credentials['uuid'])->count();
        if ($bookedSlot + 1 > $courseInfo->slot) {
            return response(['message' => 'unavaiable slot'], 204);
        }
        // $userId, $type
        $orderCode = Invoice::create($userId, 'course');
        try {
            $result = TempCourseOrder::create([
                'uuid' => Str::uuid(),
                'user_uuid' => $tokenInfo['uuid'],
                'course_uuid' => $courseInfo->uuid,
                'payment_method' => 'Bkash',
                'order_code' =>  $orderCode,
                'price' => $courseInfo->details->price,
            ]);
        } catch (Exception $e) {
            log::error($e);
            $result = false;
        }
        if ($result) {
            return Bkash::coursePaymentCreate($courseInfo->details->price, $orderCode);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    // workshop booking process
    public function bookingWorkshop($credentials, $token)
    {
        $tokenInfo = Token::decode($token);
        $exists = WorkshopOrder::where('workshop_uuid', $credentials['uuid'])->where('user_uuid', $tokenInfo['uuid'])->first();
        if ($exists) {
            return response(['message' => 'already found'], 302);
        }

        $userId = User::where('uuid', $tokenInfo['uuid'])->first()->id;
        $courseInfo = WorkShop::where('uuid', $credentials['uuid'])->select('uuid', 'instructor_uuid', 'slot')->with('details:workshop_uuid,price', 'instructor:instructor_uuid,id')->first();
        $bookedSlot = WorkshopOrder::where('uuid', $credentials['uuid'])->count();
        if ($bookedSlot + 1 > $courseInfo->slot) {
            return response(['message' => 'unavailable slot'], 204);
        }
        // $userId, $type
        $orderCode = Invoice::create($userId, 'workshop');
        try {
            $result = WorkshopOrder::create([
                'uuid' => Str::uuid(),
                'user_uuid' => $tokenInfo['uuid'],
                'workshop_uuid' => $courseInfo->uuid,
                'price' => $courseInfo->details->price,
                'payment_method' => 'FREE',
                'order_code' =>  $orderCode,
            ]);
        } catch (Exception $e) {
            log::error($e);
            return $e;
            $result = false;
        }
        if ($result) {
            return response(['message' => 'success', 'order_code' => $orderCode], 201);
        }
        return response(['message' => 'not acceptable'], 406);
    }

    public function saveWorkshop($credentials, $token)

    {

        foreach ($credentials['session_title'] as $title) {

            if (strlen($title) < 5) {

                return response(['message' => 'title must be more than 5 characters'], 406);
            }
        }

        foreach ($credentials['details'] as $details) {

            if (strlen($details) < 5) {

                return response(['message' => 'details must be more than 5 characters'], 406);
            }
        }

        foreach ($credentials['schedule'] as $schedule) {

            if (strlen($schedule) < 5) {

                return response(['message' => 'schedule must be more than 5 characters'], 406);
            }
        }

        if (count($credentials['session_title']) != count($credentials['details']) || count($credentials['session_title']) != count($credentials['schedule'])) {

            return response(['message' => 'you must fill corresponding session title, session details and session schedule'], 406);
        }



        $workshop = $this->storeWorkshop($credentials, $token);

        if ($workshop) {

            $details = $this->storeWorkshopDetails($credentials, $workshop->uuid);

            if ($details) {

                $session = $this->storeSession($credentials, $workshop->uuid);

                if ($session) {

                    return response(['message' => 'success'], 201);
                }

                $deleteCourseDetails = WorkshopDetail::where('uuid', $details['uuid'])->delete();
            }

            $deleteCourse = WorkShop::where('uuid', $workshop['uuid'])->delete();
        }

        return response(['message' => 'not acceptable'], 406);
    }

    public function storeWorkshop($credentials, $token)

    {

        $tokenInfo = Token::decode($token);

        try {

            $result = WorkShop::create([

                'uuid' => Str::uuid(),

                'instructor_uuid' => $tokenInfo['uuid'],

                'dp_category_uuid' => $credentials['dp_category_uuid'],

                'title' => $credentials['title'],

                'type' => $credentials['type'],

                'slot' => $credentials['slot'],

            ]);
        } catch (Exception $e) {

            Log::error($e);

            $result = false;
        }

        return $result;
    }

    public function storeWorkshopDetails($credentials, $workshopUuid)

    {

        try {

            $result = WorkshopDetail::create([

                'uuid' => Str::uuid(),

                'workshop_uuid' => $workshopUuid,

                'price' => $credentials['price'],

                'discount' => $credentials['discount'] ? $credentials['discount'] : null,

                'discount_duration' => $credentials['discount_duration'] ? $credentials['discount_duration'] : null,

                'discount_type' => $credentials['discount_type'] ? $credentials['discount_type'] : null,

                'language' => $credentials['language'],

                'level' => $credentials['level'],

                'summary' => $credentials['summary'],

                'cover' => $credentials['cover_uuid'],

            ]);
        } catch (Exception $e) {

            Log::error($e);

            $result = false;
        }

        return $result;
    }

    public function storeSession($credentials, $workshopUuid)

    {

        try {

            $result = WorkshopSession::create([

                'uuid' => Str::uuid(),

                'workshop_uuid' => $workshopUuid,

                'details' => json_encode($credentials['details']),

                'schedule' => json_encode($credentials['schedule']),

                'session_title' => json_encode($credentials['session_title']),

            ]);
        } catch (Exception $e) {

            Log::error($e);

            $result = false;
        }

        return  $result;
    }

    public function createWorkshopGallery($token, $file)

    {

        $fileExtension = $file->getClientOriginalExtension();

        if ($fileExtension == 'png' || $fileExtension == 'jpeg' || $fileExtension == 'jpg') {

            $type = 0;
        } else {

            $type = 1;
        }

        $path = FileSystem::storeFile($file, 'workshop/gallery');

        if ($path) {

            try {

                $tokenInfo = Token::decode($token);

                $result = WorkshopGallery::create([

                    'uuid' => Str::uuid(),

                    'instructor_uuid' => $tokenInfo['uuid'],

                    'path' => $path,

                    'type' => $type,

                ]);
            } catch (Exception $e) {

                log::error($e);

                return $e;

                $result = false;
            }
        }

        if ($result) {

            return response($result, 201);
        }

        return response(['message' => 'not acceptable'], 406);
    }

    public function deleteWorkshopGallery($credentials, $token)

    {

        $tokenInfo = Token::decode($token);

        $exist = WorkshopGallery::where('uuid', $credentials['uuid'])->where('instructor_uuid', $tokenInfo['uuid'])->first();

        try {

            $result = WorkshopGallery::where('uuid', $credentials['uuid'])->where('instructor_uuid', $tokenInfo['uuid'])->delete();

            $deleteFile = FileSystem::deleteFile($exist->path);
        } catch (Exception $e) {

            log::error($e);

            $result = false;
        }

        if ($result) {

            return response(['message' => 'deleted'], 410);
        }

        return response(['message' => 'not acceptable'], 406);
    }

    // store featured course 

    public function storeFeaturedWorkshop($credentils)

    {

        try {

            $result = FeaturedWorkshop::create([

                'uuid' => Str::uuid(),

                'workshop_uuid' => $credentils['workshop_uuid'],

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

    public function getAllFeaturedWorkshop()

    {

        return FeaturedWorkshop::with('workshop:uuid,title,type', 'workshop.details:workshop_uuid,price,cover', 'workshop.details.cover')->get();
    }

    // delete featured workshop 

    public  function deleteFeaturedWorkshop($credentils)

    {

        try {

            $result = FeaturedWorkshop::where('uuid', $credentils['uuid'])->delete();
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
