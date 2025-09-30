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
        public null|UploadedFile|string $image = null
    ) {}
}
