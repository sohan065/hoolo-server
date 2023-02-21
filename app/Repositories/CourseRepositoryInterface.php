<?php

namespace App\Repositories;

interface CourseRepositoryInterface
{
    public function getAllCourse();
    public function saveCourse($credentials, $token);
    public function storeCourse($credentials, $token);
    public function storeCourseDetails($credentials, $courseUuid);

    public function storeSession($credentials, $courseUuid);
    public function createCourseGallery($token, $file);
    public function deleteCourseGallery($credentials, $token);
    public function storeFeaturedCourse($credentils);

    public function getAllFeaturedCourse();
    public function deleteFeaturedCourse($credentils);
}
