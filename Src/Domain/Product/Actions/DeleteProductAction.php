<?php


namespace Domain\Product\Actions;

use Domain\Product\Models\Product;
use Illuminate\Support\Facades\Storage;
use Domain\Product\DataObjects\ShowOrDeleteOneProductData;

class DeleteProductAction
{
    public function execute(ShowOrDeleteOneProductData $dto)
    {
        $product =  Product::query()->whereId($dto->id)->first();

        if (!$product) {
            throw new \Exception("Product not found");
        }

        Storage::delete($product->image);
        $product->delete();

        return true;
    }
}
