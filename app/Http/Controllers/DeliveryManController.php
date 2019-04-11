<?php

namespace App\Http\Controllers;

use App\User;
use App\DeliveryManServiceOptions;
use App\ServiceRange;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateDeliveryManServiceOptions;

class DeliveryManController extends Controller
{
    public function index()
    {

    }

    public function store(UpdateDeliveryManServiceOptions $request)
    {
        DeliveryManServiceOptions::whereUserId($request->user_id)->delete();
        DeliveryManServiceOptions::create($request->all());
        return response()->json([
            'header' => 'Éxito',
            'status' => 200,
        ]);
    }

    public function show($user_id)
    {
        return response()->json(DeliveryManServiceOptions::whereUserId($user_id)->first());
    }


    public function update()
    {

    }

    public function destroy()
    {

    }

    public function get_service_ranges()
    {
        return response()->json(ServiceRange::all());
    }

}
