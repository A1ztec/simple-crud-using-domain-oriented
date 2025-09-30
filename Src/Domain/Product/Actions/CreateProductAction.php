<?php

namespace Domain\Product\Actions;

use Domain\Product\Models\Product;
use Domain\Product\DataObjects\CreateProductData;
use Domain\Product\Resources\ProductResource;

use function Support\Helpers\UploadImage;

class CreateProductAction

{
    public function execute(CreateProductData $dto) : ProductResource
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
                'image' => $path
            ]);

            return ProductResource::success(data: $product, message: "Product created successfully", code: 201);
        } catch (\Exception $e) {

            return ProductResource::error(message: "Error creating product: " . $e->getMessage(), code: 500);
        }
    }
}
