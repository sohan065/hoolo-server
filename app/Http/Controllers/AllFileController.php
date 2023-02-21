<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FileSystem;

class AllFileController extends Controller
{
    public function storeFile(Request $request)
    {
        $file = $request->file('file');
        $dir = $request->name;

        $res = FileSystem::storeFile($file, $dir);
        return response($res);
    }

    public function deleteFile(Request $request)
    {
        $path = $request->path;

        $res = FileSystem::deleteFile($path);

        return response($res);
    }
}
