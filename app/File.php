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

    public static function upload_profile_photo($model, $base64)
    {
        $url = 'public/profile_photos/' . $model->id . '_profile_photo.jpg';
        Storage::disk('local')->put($url, file_get_contents($base64));

        File::create([
            'name' => $model->id . '_profile_photo',
            'path' => $url,
            'fileable_id' => $model->id,
            'fileable_type' => get_class($model),
            'description' => 'profile_image',
        ]);
    }

    public static function delete_profile_photo($url)
    {
        Storage::disk('local')->delete($url);

        File::where('path', $url)->delete();
    }

}
