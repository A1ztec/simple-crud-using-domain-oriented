<?php

namespace Domain\Product\Actions;

use Domain\Product\Models\Product;
use Domin\Product\DataObjects\CreateProductData;
use function Support\Helpers\UploadImage;

class CreateProductAction

{
    public function execute(CreateProductData $dto)
    {

        if ($dto->image && is_file($dto->image)) {
            $path = UploadImage($dto->image, 'products');
            return $path;
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
            return $product;
        } catch (\Exception $e) {
            throw new \Exception("Error creating product: " . $e->getMessage());
        }
    }
}
