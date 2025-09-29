<?php

namespace Domain\Product\Actions;

use Domain\Product\Models\Product;
use Illuminate\Support\Facades\Storage;

use function Support\Helpers\UploadImage;
use Domain\Product\Resources\ProductResource;
use Domain\Product\DataObjects\UpdateProductData;

class UpdateProductAction
{

    public function execute(UpdateProductData $dto)
    {
        $product = Product::query()->whereId($dto->id)->first();


        if (!$product) {
            return ProductResource::error(message: "Product not found", code: 404);
        }

        if ($dto->image && is_file($dto->image)) {
            Storage::delete($product->image);
            $path = UploadImage($dto->image, 'products');
            $dto->image = $path;
        }

        $data = collect((array)$dto)->filter(fn($value) => !is_null($value))->except('id')->toArray();

        try {
            $product->update($data);
            return ProductResource::success(data: $product, message: "Product updated successfully", code: 200);
        } catch (\Exception $e) {
            return ProductResource::error(message: "Error updating product: " . $e->getMessage(), code: 500);
        }
    }
}
