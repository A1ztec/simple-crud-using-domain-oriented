<?php

namespace Domain\Product\DataObjects;

use Illuminate\Http\UploadedFile;

class CreateProductData
{
    public function __construct(
        public string $name,
        public ?string $description,
        public int $quantity,
        public float $price,
        public null|string|UploadedFile $image
    ) {}


    public static function FromRequest(array $data): CreateProductData
    {
        return new CreateProductData(
            name: $data['name'],
            description: $data['description'] ?? null,
            quantity: $data['quantity'],
            price: $data['price'],
            image: $data['image'] ?? null,
        );
    }
    public function setData(array $data): CreateProductData
    {
        return new CreateProductData(...$data);
    }
}
