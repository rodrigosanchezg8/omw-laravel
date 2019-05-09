<?php
/**
 * Created by PhpStorm.
 * User: rodri
 * Date: 09/05/2019
 * Time: 16:19
 */

namespace App\Services;

use App\File;
use App\Message;
use Carbon\Carbon;
use App\Events\NewMessage;
use Illuminate\Support\Facades\Event;

class MessageService
{
    static function create($request, $delivery, $user)
    {
        $message = new Message();
        $message->body = $request->body;
        $message->delivery()->associate($delivery);
        $message->replier()->associate($user);
        $message->receiver()->associate($request->user_id_receiver);
        $message->save();

        if ($request->file) {
            File::upload_file($delivery, $request->file, 'delivery_message');
        }

        $delivery->updated_at = Carbon::now();
        $delivery->save();

        Event::fire(new NewMessage($message));
    }

}