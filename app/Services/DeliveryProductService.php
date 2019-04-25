<?php

namespace App\Services;

use App\File;
use App\DeliveryProduct;
use Illuminate\Support\Facades\Log;

class DeliveryProductService
{
    public function getDeliveryProductByDelivery($deliveryId)
    {
        return DeliveryProduct::whereDeliveryId($deliveryId)->get();
    }

    public function getDetailedDeliveryProduct($deliveryId)
    {
        return DeliveryProduct::find($deliveryId);
    }

    public function create($data)
    {
        $deliveryProduct = DeliveryProduct::create($data);

        if (isset($data['product_image']) && FileService::isBase64Image($data['product_image'])) {
            File::upload_file($deliveryProduct, $data['product_image'], 'product_image');
        }

        return $deliveryProduct;
    }

    public function update(DeliveryProduct $deliveryProduct, $data)
    {
        $deliveryProduct->update($data);

        if (isset($data['product_image']) && FileService::isBase64Image($data['product_image'])) {
            File::upload_file($deliveryProduct, $data['product_image'], 'product_image');
        }
    }

    public function delete(DeliveryProduct $deliveryProduct)
    {
        if ($deliveryProduct->productImage()) {
            File::delete_file($deliveryProduct->productImage()->path);
        }

        $deliveryProduct->delete();
    }

}
