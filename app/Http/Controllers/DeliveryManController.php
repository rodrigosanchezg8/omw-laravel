<?php

namespace App\Http\Controllers;

use App\DeliveryMan;
use App\ServiceRange;
use Illuminate\Http\Request;
use App\Http\Requests\SignUpDeliveryMan;

class DeliveryManController extends Controller
{
    public function index()
    {

    }

    public function store(SignUpDeliveryMan $request)
    {
        DeliveryMan::create($request->all());
        return response()->json([
            'header' => 'Éxito',
            'status' => 200,
        ]);
    }

    public function show()
    {

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
