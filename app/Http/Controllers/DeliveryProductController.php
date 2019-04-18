<?php

namespace App\Http\Controllers;

use App\DeliveryProduct;
use App\Http\Requests\DeliveryProductStore;
use App\Services\DeliveryProductService;
use Illuminate\Http\Request;

class DeliveryProductController extends Controller
{
    public function __construct(DeliveryProductService $service)
    {
        $this->service = $service;
    }

    public function store(DeliveryProductStore $request)
    {
        $deliveryProduct = $this->service->store($request->all());
    }
}
