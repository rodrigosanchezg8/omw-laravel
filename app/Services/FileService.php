<?php

namespace App\Services;

class FileService
{

    public static function isBase64Image($base64)
    {
        return preg_match('/data:image\/([a-zA-Z]*)/', $base64, $match);
    }

}