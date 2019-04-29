<?php

namespace App\Services;

use App\Delivery;
use App\File;
use App\DeliveryProduct;
use Illuminate\Support\Facades\Auth;
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
        $delivery = Delivery::find($data['delivery_id']);

        if (Auth::user()->isReceivingDelivery($delivery)) {

            throw new \Exception("No puedes agregar productos en esta entrega", 1);

        }

        if (!$delivery->canBeAltered()) {
            throw new \Exception("El estatus de la entega no permite agregar productos", 1);
        }

        $deliveryProduct = DeliveryProduct::create($data);

        if (isset($data['product_image']) && FileService::isBase64Image($data['product_image'])) {
            File::upload_file($deliveryProduct, $data['product_image'], 'product_image');
        }

        return $deliveryProduct;
    }

    public function update(DeliveryProduct $deliveryProduct, $data)
    {
        if (Auth::user()->isReceivingDelivery($deliveryProduct->delivery)) {

            throw new \Exception("No puedes actualizar este producto", 1);

        }

        if (!$deliveryProduct->delivery->canBeAltered()) {

            throw new \Exception("El estatus de la entega no permite actualizar productos", 1);

        }

        $deliveryProduct->update($data);

        if (isset($data['product_image']) && FileService::isBase64Image($data['product_image'])) {

            File::upload_file($deliveryProduct, $data['product_image'], 'product_image');

        }
    }

    public function destroy(DeliveryProduct $deliveryProduct)
    {
        if (Auth::user()->isReceivingDelivery($deliveryProduct->delivery)) {

            throw new \Exception("No puedes borrar productos que no te corresponden", 1);

        }

        if (!$deliveryProduct->delivery->canBeAltered()) {

            throw new \Exception("El estatus de la entega no permite eliminar productos", 1);

        }

        if ($deliveryProduct->productImage()) {

            File::delete_file($deliveryProduct->productImage()->path);

        }

        $deliveryProduct->delete();
    }

}
