<?php


namespace Domain\Order\Actions;

use Domain\Product\Models\Product;
use Illuminate\Support\Collection;
use Domain\Order\DataObjects\CreateOrderDto;
use Domain\Order\Resources\CreateOrderFailedResource;
use Domain\Order\Resources\ValidateOrderCreationFailedResource;
use Domain\Order\Resources\ValidateOrderCreationSuccessResource;

class ValidateOrderCreationData
{
    public function __invoke(CreateOrderDto $dto, Collection $products)
    {

        if ($products->count() !== count($dto->items)) {
            return new ValidateOrderCreationFailedResource(message: 'One or more products not found in my inventory');
        }

        foreach ($dto->items as $item) {
            $product = $products->get($item->productId);

            if (!$product) {
                return new ValidateOrderCreationFailedResource(message: 'Product not found in my inventory');
            }

            if ($product->quantity < $item->quantity) {
                return new ValidateOrderCreationFailedResource(message: 'not enough product quantity in my inventory');
            }
        }

        return new ValidateOrderCreationSuccessResource();
    }
}
