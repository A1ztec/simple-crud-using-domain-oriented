<?php

namespace Application\User\ViewModels;

use Support\Traits\apiResponse;
use Domain\User\Resources\UserResource;
use League\Fractal\Serializer\JsonApiSerializer;
use Application\User\Transformers\UserTransformer;


class UserViewModel
{
    use apiResponse;
    public function __construct(private UserResource $resource) {}

    public function toResponse()
    {
        if (!$this->resource->isSuccess()) {
            return $this->errorResponse(message: $this->resource->getMessage(), code: $this->resource->getCode());
        }
        if (empty($this->resource->getData()) && $this->resource->isSuccess()) {
            return $this->successResponse(code: $this->resource->getCode(), message: $this->resource->getMessage());
        }

        //  dd(function_exists('fractal'));
        // dd($this->resource->getData());

        $data = fractal()->item($this->resource->getData())
            ->transformWith(new UserTransformer())
            ->serializeWith(new JsonApiSerializer())
            ->toArray();

        return $this->successResponse(code: $this->resource->getCode(), message: $this->resource->getMessage(), data: $data);
    }
}
