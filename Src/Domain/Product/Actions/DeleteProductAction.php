<?php


namespace Domain\Product\Actions;

use Domain\Product\Models\Product;
use Domain\Product\Resources\DeleteProductSuccessResource;
use Illuminate\Support\Facades\Storage;
use Domain\Product\DataObjects\ShowOrDeleteOneProductData;
use Domain\Product\Resources\DeleteProductFailedResource;
use Domain\Product\Resources\ProductResource;
use Src\Domain\Product\Resources\Contracts\ProductResourceInterface;

class DeleteProductAction
{
    public function execute(ShowOrDeleteOneProductData $dto): ProductResourceInterface
    {
        $product =  Product::query()->whereId($dto->id)->first();

        if (!$product) {
            return new DeleteProductFailedResource();
        }


        if ($product->image) {
            if (Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
        }

        $product->delete();

        return new DeleteProductSuccessResource();
    }
}
