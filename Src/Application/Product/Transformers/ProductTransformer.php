<?php

namespace Application\Product\Transformers;

use Illuminate\Support\Facades\Storage;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    public function transform($data)
    {
        if (!$data) {
            return [];
        }

        return [
            'id' => $data->id ?? null,
            'name' => $data->name ?? null,
            'description' => $data->description ?? null,
            'price' => $data->price ?? null,
            'image' => $data->image ? url($data->image) : null,
        ];
    }
}
