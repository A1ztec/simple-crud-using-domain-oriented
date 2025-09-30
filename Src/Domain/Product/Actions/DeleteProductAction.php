<?php


namespace Domain\Product\Actions;

use Domain\Product\Models\Product;
use Illuminate\Support\Facades\Storage;
use Domain\Product\DataObjects\ShowOrDeleteOneProductData;
use Domain\Product\Resources\ProductResource;

class DeleteProductAction
{
    public function execute(ShowOrDeleteOneProductData $dto) : ProductResource
    {
        $product =  Product::query()->whereId($dto->id)->first();

        if (!$product) {
            return ProductResource::error(message: "Product not found", code: 404);
        }


        if ($product->image) {
            if (Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
        }

        $product->delete();

        return ProductResource::success(message: "Product deleted successfully");
    }
}
