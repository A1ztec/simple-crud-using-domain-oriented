<?php

namespace Domain\Product\Actions;

use Domain\Product\Models\Product;
use Illuminate\Support\Facades\Storage;

use function Support\Helpers\UploadImage;
use Domain\Product\DataObjects\UpdateProductData;
use Domain\Product\Resources\UpdateProductFailedResource;
use Domain\Product\Resources\UpdateProductSuccessResource;
use Domain\Product\Resources\Contracts\ProductResourceInterface;

class UpdateProductAction
{

    public function execute(UpdateProductData $dto): ProductResourceInterface
    {
        $product = Product::query()->whereId($dto->id)->first();




        if (!$product) {
            return new UpdateProductFailedResource();
        }


        if ($dto->image && is_file($dto->image)) {

            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }


            $path = UploadImage($dto->image, 'products');
            $dto->image = $path;
        }

        $data = collect((array)$dto)->filter(fn($value) => !is_null($value))->except('id')->toArray();

        //dd($data);

        try {
            $product->update($data);
            return new UpdateProductSuccessResource();
        } catch (\Exception $e) {
            return new UpdateProductFailedResource();
        }
    }
}
