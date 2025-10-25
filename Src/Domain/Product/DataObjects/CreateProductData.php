<?php

namespace Domain\Product\DataObjects;

use Illuminate\Http\UploadedFile;

class CreateProductData
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        public int $quantity,
        public float $price,
        public null|string|UploadedFile $image = null
    ) {}
}
