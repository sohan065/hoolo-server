<?php



namespace App\Services;



use Token;

use Exception;

use FileSystem;

use App\Models\Post;

use App\Models\Product;

use App\Models\PostLike;

use App\Models\PostComment;

use App\Models\PostGallery;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Repositories\PostRepositoryInterface;



class PostRepositoryservices implements PostRepositoryInterface

{
     public $seconds = 60 * 20;
    public function getPost($uuid)
    {
       return Post::where('uuid', $uuid)->with(

                'author.info:merchant_uuid,company_logo',

                'product:id,uuid,name',

                'product.details:product_uuid,price,cover,stock,discount,discount_type,discount_duration',

                'product.details.cover',

                'gallery',

                'like',

                'comment.userInfo:user_uuid,user_name',

                'comment.profile:user_uuid,path',

                'comment.reply.userInfo:user_uuid,user_name',

                'comment.reply.profile:user_uuid,path',

            )->first();
        // return Cache::remember('getPost_'.$uuid, $this->seconds, function () use ($uuid) {
        //     return Post::where('uuid', $uuid)->with(

        //         'author.info:merchant_uuid,company_logo',

        //         'product:id,uuid,name',

        //         'product.details:product_uuid,price,cover,stock',

        //         'product.details.cover',

        //         'gallery',

        //         'like',

        //         'comment.userInfo:user_uuid,user_name',

        //         'comment.profile:user_uuid,path',

        //         'comment.reply.userInfo:user_uuid,user_name',

        //         'comment.reply.profile:user_uuid,path',

        //     )->first();
        // });
    }

    public function getAllPost()
    {

        return Post::with(

                'author.info:merchant_uuid,company_logo',

                'product:id,uuid,name',

                'product.details:product_uuid,price,cover,stock,discount,discount_type,discount_duration',

                'product.details.cover',

                'gallery',

                'like',

                'comment.userInfo:user_uuid,user_name',

                'comment.profile:user_uuid,path',

                'comment.reply.userInfo:user_uuid,user_name',

                'comment.reply.profile:user_uuid,path',

            )->orderBy('id', 'DESC')->paginate(30);
    }

    // increase post view

    public function increaseView($validated)

    {

        $exists = Post::where('uuid', $validated)->first();

        if ($exists) {

            $views = $exists->views;

            $exists->views = $views + 1;

            $result =  $exists->update();

            if ($result) {

                return response(['message' => 'success'], 202);
            }
        }

        return response(['message' => 'not found'], 406);
    }

    // increase post share

    public function increaseShare($credentials)

    {

        $exists = Post::where('uuid', $credentials['uuid'])->first();

        if ($exists) {

            $share = $exists->share;

            $exists->share = $share + 1;

            $result =  $exists->update();

            if ($result) {

                return response(['message' => 'updated'], 202);
            }
        }

        return response(['message' => 'not found'], 406);
    }

    public function store($credentials, $token)

    {

        $tokenInfo = Token::decode($token);

        $fileIdList = array();

        foreach ($credentials['file_uuid'] as $file) {

            $exists = PostGallery::where('uuid', $file)->where('merchant_uuid', $tokenInfo['uuid'])->first();

            if (!$exists) {

                $response = ['file_uuid' => array('invalid file uuid')];

                return response($response, 422);
            }

            array_push($fileIdList, $exists->id);
        }

        $productIdList = array();

        foreach ($credentials['product_uuid'] as $product) {

            $exists = Product::where('uuid', $product)->where('merchant_uuid', $tokenInfo['uuid'])->first();

            if (!$exists) {

                $response = ['product_uuid' => array('invalid product uuid')];

                return response($response, 422);
            }

            array_push($productIdList, $exists->id);
        }

        try {

            $result = Post::create([

                'uuid' => Str::uuid(),

                'merchant_uuid' => $tokenInfo['uuid'],

                'product_uuid' => $productIdList,

                'file_uuid' => $fileIdList,

                'title' => $credentials['title'],

                'type' => $credentials['type'],

            ]);
        } catch (Exception $e) {

            Log::error($e);

            $result = false;
        }

        if ($result) {

            return response(['message' => 'success'], 201);
        }

        return response(['message' => 'not acceptable'], 402);
    }

    public function delete($credentials, $token)

    {

        $tokenInfo = Token::decode($token);

        try {

            $result = Post::where('uuid', $credentials['uuid'])->where('merchant_uuid', $tokenInfo['uuid'])->delete();
        } catch (Exception $e) {

            log::error($e);



            $result = false;
        }

        if ($result) {

            return response(['message' => 'success'], 202);
        }

        return response(['message' => 'not acceptable'], 402);
    }

    public function storePostLike($credentials, $token)

    {

        $tokenInfo = Token::decode($token);

        $exists = PostLike::where('post_uuid', $credentials['post_uuid'])->first();

        $result = false;

        if ($exists) {

            $users = $exists->user_uuid;

            if ($key = array_search($tokenInfo['uuid'], $users)) {

                unset($users[$key]);

                try {

                    $result = PostLike::where('post_uuid', $credentials['post_uuid'])->update([

                        'user_uuid' => $users,

                    ]);
                } catch (Exception $e) {

                    log::error($e);

                    $result = false;
                }
            } else {

                array_push($users, $tokenInfo['uuid']);

                try {

                    $result = PostLike::where('post_uuid', $credentials['post_uuid'])->update([

                        'user_uuid' => $users,

                    ]);
                } catch (Exception $e) {

                    log::error($e);

                    $result = false;
                }
            }
        } else {

            try {

                $result = PostLike::create([

                    'uuid' => Str::uuid(),

                    'post_uuid' => $credentials['post_uuid'],

                    'user_uuid' => array(null, $tokenInfo['uuid']),

                ]);
            } catch (Exception $e) {

                log::error($e);

                $result = false;
            }
        }



        if ($result) {

            $result = PostLike::where('post_uuid', $credentials['post_uuid'])->first();

            return response($result, 201);
        }

        return response(['message' => 'not acceptable'], 406);
    }

    public function storePostComment($credentials, $token, $file)

    {

        $tokenInfo = Token::decode($token);

        $path = null;

        if ($file) {

            $path = FileSystem::storeFile($file, 'comment/attachtment');
        }

        try {

            $result = PostComment::create([

                'uuid' => Str::uuid(),

                'post_uuid' => $credentials['post_uuid'],

                'user_uuid' => $tokenInfo['uuid'],

                'parent_uuid' => $credentials['parent_uuid'],

                'comment' => $credentials['comment'],

                'attachment' => $path,

            ]);

            $result = PostComment::where('uuid', $result->uuid)->with('userInfo:user_uuid,user_name', 'profile:user_uuid,path')->first();
        } catch (Exception $e) {

            return $e;

            Log::error($e);

            $result = false;
        }

        if ($result) {

            return response($result, 201);
        }

        $deleteFile = FileSystem::deleteFile($path);

        return response(['message' => 'not acceptable'], 406);
    }

    // update post comment 

    public function updatePostComment($credentials, $token)

    {

        $tokenInfo = Token::decode($token);

        try {

            $result = PostComment::where('uuid', $credentials['uuid'])->where('user_uuid', $tokenInfo['uuid'])->update([

                'comment' => $credentials['comment'],

            ]);
        } catch (Exception $e) {

            log::error($e);

            $result = false;
        }

        if ($result) {

            $updateData = PostComment::where('uuid', $credentials['uuid'])->where('user_uuid', $tokenInfo['uuid'])->first();

            return response($updateData, 201);
        }

        // $deleteFile = FileSystem::deleteFile($path);

        return response(['message' => 'not acceptable'], 406);
    }

    // delete post comment 

    public function deletePostComment($credentials, $token)

    {

        $tokenInfo = Token::decode($token);

        try {

            $result = PostComment::where('user_uuid', $tokenInfo['uuid'])->where('uuid', $credentials['uuid'])->orWhere('parent_uuid', $credentials['uuid'])->delete();
        } catch (Exception $e) {

            log::error($e);

            $result = false;
        }

        if ($result) {

            return response(['message' => 'deleted'], 410);
        }

        // $deleteFile = FileSystem::deleteFile($path);

        return response(['message' => 'not acceptable'], 406);
    }
}
