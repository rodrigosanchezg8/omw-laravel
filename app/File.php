<?php

namespace App;

use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'name',
        'path',
        'fileable_id',
        'fileable_type',
        'description',
        'status',
    ];

    public $timestamps = false;

    public static function upload_file($model, $base64, $name)
    {
        $folder = $name . 's';
        $file_name = $model->id . '_' . $name . '.' . File::get_base_64_extension($base64);
        Storage::disk('public')->put($folder . '/' . $file_name, file_get_contents($base64));

        File::create([
            'name' => $file_name,
            'path' => $folder,
            'fileable_id' => $model->id,
            'fileable_type' => get_class($model),
            'description' => $name
        ]);
    }

    public static function delete_file($url)
    {
        Storage::disk('local')->delete($url);

        File::where('path', $url)->delete();
    }

    private static function get_base_64_extension($uri)
    {
        $img = explode(',', $uri);
        $ini = substr($img[0], 11);
        $type = explode(';', $ini);
        return $type[0];
    }

}
