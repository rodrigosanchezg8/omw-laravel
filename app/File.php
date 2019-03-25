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
        'cloud_path',
        'status',
    ];

    public $timestamps = false;

    public function upload_profile_photo($model, $image)
    {
        $url = 'public/profile_photos/' . $model->id . '_profile_photo.jpg';

        Storage::disk('local')->put($url, $image);

        DB::table('files')->insert([
            'name' => $model->id . '_profile_photo',
            'path' => $url,
            'fileable_id' => $model->id,
            'fileable_type' => get_class($model),
            'description' => 'profile_image',
        ]);
    }

}
