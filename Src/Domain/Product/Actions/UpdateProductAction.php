<?php

namespace Domain\Product\Actions;

use Domain\Product\Models\Product;
use Illuminate\Support\Facades\Storage;

use function Support\Helpers\UploadImage;
use Domain\Product\DataObjects\UpdateProductData;

class UpdateProductAction
{

    public function execute(UpdateProductData $dto)
    {
        $product = Product::query()->whereId($dto->id)->first();


        if (!$product) {
            throw new \Exception("Product not found");
        }

        if ($dto->image && is_file($dto->image)) {
            Storage::delete($product->image);
            $path = UploadImage($dto->image, 'products');
            $dto->image = $path;
        }

        $product->update($dto);

        return $product;
    }
}
