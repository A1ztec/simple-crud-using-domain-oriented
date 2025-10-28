<?php

namespace Domain\Product\DataObjects;

class ProductData
{
    public function __construct(
        public ?int $id,
        public string $name,
        public ?string $description,
        public int $quantity,
        public float $price,
        public ?string $image,
        public ?string $created_at,
        public ?string $updated_at,
    ) {}



    public static function setData(array $data): ProductData
    {
        return new ProductData(...$data);
    }
}
