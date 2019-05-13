<?php

namespace App\Http\Controllers;

use App\User;
use App\Delivery;
use Illuminate\Http\Request;
use App\Services\MessageService;
use App\Events\DeliveryMessagesHistoryRequested;
use Illuminate\Support\Facades\Event;

class MessageController extends Controller
{
    public function __construct(MessageService $messageService)
    {
        $this->service = $messageService;
    }

    public function index(Delivery $delivery, $start_message_id = 1)
    {
        try {

            Event::dispatch(new DeliveryMessagesHistoryRequested($delivery, $start_message_id));

            return response()->json([
                'header' => 'Mensajes de la entrega recuperados',
                'status' => 'success'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function store(Request $request, Delivery $delivery)
    {
        try {

            $this->service->create($request, $delivery);

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
