<?php

namespace App\Http\Controllers;

use PostGallery;
use Validator;
use Illuminate\Http\Request;

class PostGalleryController extends Controller
{
    public function createPostGallery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'bail|required|file|mimes:mp4,mov,jpeg',
        ]);
        $token = $request->header('token');
        $file = $request->file('file');
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        return PostGallery::createPost($token, $file);
    }

    public function deletePostGallery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uuid' => 'bail|required|string|exists:post_galleries,uuid',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 422);
        }
        $token = $request->header('token');
        $validated = $request->only(['uuid']);

        return PostGallery::deletePostGallery($validated, $token);
    }
}
