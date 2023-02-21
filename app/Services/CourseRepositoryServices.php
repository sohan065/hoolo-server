<?php



namespace App\Services;



use SSL;
use Bkash;
use Token;
use Invoice;
use Exception;
use FileSystem;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Str;
use App\Models\CourseDetail;
use App\Models\CourseGallery;
use App\Models\CourseOrder;
use App\Models\CourseSession;
use App\Models\FeaturedCourse;
use App\Models\TempCourseOrder;
use Illuminate\Support\Facades\Log;
use App\Repositories\CourseRepositoryInterface;


class CourseRepositoryServices implements CourseRepositoryInterface

{

    public function getAllCourse()

    {

        return Course::with('instructor:instructor_uuid,full_name,user_name', 'profile', 'dpCategory','details.cover','session')->orderBy('id', 'DESC')->paginate(30);

    }

    public function getCourseByInstructor($uuid)
    {
        return Course::where('instructor_uuid',$uuid)->with('instructor:instructor_uuid,full_name,user_name', 'profile', 'dpCategory','details.cover','session')->orderBy('id', 'DESC')->paginate(30);
    }
    public function getCourseByDpCategory($uuid)
    {
        return Course::where('dp_category_uuid',$uuid)->with('instructor:instructor_uuid,full_name,user_name', 'profile', 'dpCategory','details.cover','session')->orderBy('id', 'DESC')->paginate(30);
    }

     // get course details by uuid

    public function getCourseDetails($credentials)

    {

        return Course::where('uuid', $credentials['uuid'])->with('instructor:uuid,instructor_uuid,full_name,user_name,about_me', 'profile', 'details.cover', 'session')->orderBy('id', 'DESC')->withCount('order')->first();

       

    }

        // get  all user course 

    public function getAllUserCourse($token)

    {

        $tokenInfo = Token::decode($token);

        return CourseOrder::where('user_uuid', $tokenInfo['uuid'])->with('course.profile', 'course.dpCategory','course.details.cover','course.session','course.instructor:instructor_uuid,full_name,user_name')->orderBy('id', 'DESC')->get();

    }

     // course order by CARD
    public function orderCourseByCard($credentials, $token)
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
        $totalPrice = $courseInfo->details->price;
        // $userId, $type
        $orderCode = Invoice::create($userId, 'course');
        try {
            $result = TempCourseOrder::create([
                'uuid' => Str::uuid(),
                'user_uuid' => $tokenInfo['uuid'],
                'course_uuid' => $courseInfo->uuid,
                'payment_method' => 'CARD',
                'order_code' =>  $orderCode,
                'price' => $courseInfo->details->price,
            ]);
        } catch (Exception $e) {
            log::error($e);
            $result = false;
        }
        if ($result) {
            return SSL::courseOrderByCard($token, $totalPrice, $orderCode);
        }
        return response(['message' => 'not acceptable'], 406);
    }
    // course order  by BKASH
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
    // course booking process
    public function bookingCourse($credentials, $token)
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
            return response(['message' => 'unavailable slot'], 204);
        }
        // $userId, $type
        $orderCode = Invoice::create($userId, 'course');
        try {
            $result = CourseOrder::create([
                'uuid' => Str::uuid(),
                'user_uuid' => $tokenInfo['uuid'],
                'course_uuid' => $courseInfo->uuid,
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

    public function saveCourse($credentials, $token)

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



       $course = $this->storeCourse($credentials, $token);

        if ($course) {

            $details = $this->storeCourseDetails($credentials, $course->uuid);

            if ($details) {

                $session = $this->storeSession($credentials, $course->uuid);

                if ($session) {

                    return response(['message' => 'success'], 201);

                }

                $deleteCourseDetails = CourseDetail::where('uuid', $details['uuid'])->delete();

            }

            $deleteCourse = Course::where('uuid', $course['uuid'])->delete();

        }

        return response(['message' => 'not acceptable'], 406);

    }

    public function storeCourse($credentials, $token)

    {

        $tokenInfo = Token::decode($token);

        try {

            $result = Course::create([

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

    public function storeCourseDetails($credentials, $courseUuid)

    {

        try {

            $result = CourseDetail::create([

                'uuid' => Str::uuid(),

                'course_uuid' => $courseUuid,

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

    public function storeSession($credentials, $courseUuid)

    {

        try {

            $result = CourseSession::create([

                'uuid' => Str::uuid(),

                'course_uuid' => $courseUuid,

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

    public function createCourseGallery($token, $file)

    {

        $fileExtension = $file->getClientOriginalExtension();

        if ($fileExtension == 'png' || $fileExtension == 'jpeg' || $fileExtension == 'jpg') {

            $type = 0;

        } else {

            $type = 1;

        }

        $path = FileSystem::storeFile($file, 'course/gallery');

        if ($path) {

            try {

                $tokenInfo = Token::decode($token);

                $result = CourseGallery::create([

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

    public function deleteCourseGallery($credentials, $token)

    {

        $tokenInfo = Token::decode($token);

        $exist = CourseGallery::where('uuid', $credentials['uuid'])->where('creator_uuid', $tokenInfo['uuid'])->first();

        try {

            $result = CourseGallery::where('uuid', $credentials['uuid'])->where('creator_uuid', $tokenInfo['uuid'])->delete();

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

    public function storeFeaturedCourse($credentils)

    {

        try {

            $result = FeaturedCourse::create([

                'uuid' => Str::uuid(),

                'course_uuid' => $credentils['course_uuid'],

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

    public function getAllFeaturedCourse()

    {

        return FeaturedCourse::with('course:uuid,title,type','course.details:course_uuid,price,cover','course.details.cover')->orderBy('id', 'DESC')->get();

    }

    // delete featured course 

    public  function deleteFeaturedCourse($credentils)

    {

        try {

            $result = FeaturedCourse::where('uuid', $credentils['uuid'])->delete();

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

