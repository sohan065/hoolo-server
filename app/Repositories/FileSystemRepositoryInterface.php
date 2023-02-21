<?php

namespace App\Repositories;

interface FileSystemRepositoryInterface
{
    public function storeFile($file, $directory);
    public function deleteFile($path);
}
