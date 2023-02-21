<?php



namespace App\Http\Controllers;



use Post;

use Validator;

use Illuminate\Http\Request;



class PostController extends Controller

{
    public function getPost(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:posts,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);
        return Post::getPost($validated['uuid']);
    }

    public function getAllPost()

    {

        return Post::getAllPost();
    }

    public function increaseView(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:posts,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return Post::increaseView($validated);
    }

    // incease post share

    public function increaseShare(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|exists:posts,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['uuid']);

        return Post::increaseShare($validated);
    }

    public function createPost(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'title' => 'bail|required|string|min:3',

            'product_uuid' => 'bail|required|array|min:1',

            'file_uuid' => 'bail|required|array|min:1',

            'type' => 'bail|required|integer|max:2',

        ]);

        $token = $request->header('token');

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $validated = $request->only(['title', 'product_uuid', 'file_uuid', 'type']);

        return Post::store($validated, $token);
    }

    public function deletePost(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|required|string|min:3|exists:posts,uuid',

        ]);



        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $token = $request->header('token');

        $validated = $request->only(['uuid']);

        return Post::delete($validated, $token);
    }

    public function storePostLike(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'post_uuid' => 'bail|required|string|exists:posts,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $token = $request->header('token');

        $validated = $request->only(['post_uuid']);

        return Post::storePostLike($validated, $token);
    }

    public function storePostComment(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'post_uuid' => 'bail|nullable|string|exists:posts,uuid',

            'parent_uuid' => 'bail|nullable|string|exists:post_comments,uuid',

            'comment' => 'bail|required|string|max:500',

            'attachment' => 'bail|nullable|mimes:jpg,jpeg,png,pdf',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $file = $request->file('attachment');

        $token = $request->header('token');

        $validated = $request->only(['post_uuid', 'parent_uuid', 'comment']);

        return Post::storePostComment($validated, $token, $file);
    }

    // update post comment

    public function updatePostComment(Request $request)

    {

        $validator = Validator::make($request->all(), [

            // 'post_uuid' => 'bail|required|string|exists:posts,uuid',

            'uuid' => 'bail|nullable|string|exists:post_comments,uuid',

            'comment' => 'bail|required|string|min:1|max:500',

            // 'attachment' => 'bail|nullable|mimes:jpg,jpeg,png,pdf',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        // $file = $request->file('attachment');

        $token = $request->header('token');

        $validated = $request->only(['uuid', 'comment']);

        return Post::updatePostComment($validated, $token);
    }

    // delete post comment by comment uuid

    public function deletePostComment(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'uuid' => 'bail|nullable|string|exists:post_comments,uuid',

        ]);

        if ($validator->fails()) {

            return response($validator->messages(), 422);
        }

        $token = $request->header('token');

        $validated = $request->only(['uuid']);

        return Post::deletePostComment($validated, $token);
    }
}
