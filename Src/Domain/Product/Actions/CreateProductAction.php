<?php

namespace Domain\Product\Actions;

use Domain\Product\Models\Product;
use function Support\Helpers\UploadImage;
use Domain\Product\DataObjects\CreateProductData;
use Domain\Product\Resources\CreateProductFailedResource;
use Domain\Product\Resources\CreateProductSuccessResource;
use Domain\Product\Resources\Contracts\ProductResourceInterface;

class CreateProductAction

{
    public function execute(CreateProductData $dto): ProductResourceInterface
    {

        if ($dto->image && is_file($dto->image)) {
            $path = UploadImage($dto->image, 'products');
        } else {
            $path = null;
        }

        try {
            $product =  Product::create([
                'name' => $dto->name,
                'description' => $dto->description,
                'price' => $dto->price,
                'quanity' => $dto->quantity,
                'image' => $path
            ]);

            return new CreateProductSuccessResource(data: $product);
        } catch (\Exception $e) {

            return new CreateProductFailedResource();
        }
    }
}
