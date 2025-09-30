<?php

namespace Application\Product\Transformers;

use Illuminate\Support\Facades\Storage;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    public function transform($data)
    {

        return [
            'id' => $data->id,
            'name' => $data->name,
            'description' => $data->description,
            'price' => $data->price,
            'image' => $data->image ? url($data->image) : null,
        ];
    }
}
