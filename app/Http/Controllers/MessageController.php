<?php

namespace App\Http\Controllers;

use App\User;
use App\Delivery;
use Illuminate\Http\Request;
use App\Services\MessageService;

class MessageController extends Controller
{
    public function __construct(MessageService $messageService)
    {
        $this->service = $messageService;
    }

    public function store(Request $request, Delivery $delivery, User $user)
    {
        try {

            $this->service->create($request, $delivery, $user);

            return response()->json([
                'header' => 'Mensaje enviado',
                'status' => 'success'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

}
