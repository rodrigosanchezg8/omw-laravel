<?php

namespace App\Services;

use App\DeliveryProduct;
use App\File;

class DeliveryProductService
{
    public function store($data)
    {
        $deliveryProduct = DeliveryProduct::create($data);

        if (isset($data['product_image']) && FileService::isBase64Image($data['product_image'])) {

            File::upload_file($deliveryProduct, $data['product_image'], 'product_image');

        }

        return $deliveryProduct;
    }
}
