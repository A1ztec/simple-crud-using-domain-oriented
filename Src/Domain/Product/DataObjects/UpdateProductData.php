<?php


namespace Domain\Product\DataObjects;

class UpdateProductData
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $description,
        public ?float $price,
        public ?string $image
    ) {}
}
