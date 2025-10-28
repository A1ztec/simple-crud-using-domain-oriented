<?php

namespace Domain\Product\DataObjects;

use Illuminate\Http\UploadedFile;

class UpdateProductData
{
    public function __construct(
        public int $id,
        public ?string $name = null,
        public ?string $description = null,
        public ?float $price = null,
        public ?int $quantity = null,
        public null|UploadedFile|string $image = null
    ) {}


    public static function fromRequest(array $data): UpdateProductData
    {
        return new UpdateProductData(
            id: $data['id'],
            name: $data['name'] ?? null,
            description: $data['description'] ?? null,
            price: $data['price'] ?? null,
            quantity: $data['quantity'] ?? null,
            image: $data['image'] ?? null,
        );
    }
}
