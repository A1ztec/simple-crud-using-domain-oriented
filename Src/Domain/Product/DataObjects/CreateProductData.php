<?php

namespace Domin\Product\DataObjects;


class CreateProductData
{
    public function __construct(
        public string $name,
        public string $description,
        public float $price,
        public ?string $image
    ) {}
}
