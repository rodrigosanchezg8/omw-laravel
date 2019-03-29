<?php

namespace App\Http\Controllers;

use App\DeliveryMan;
use Illuminate\Http\Request;

class DeliveryManController extends Controller
{
    public function index()
    {

    }

    public function store(Request $request)
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

}
