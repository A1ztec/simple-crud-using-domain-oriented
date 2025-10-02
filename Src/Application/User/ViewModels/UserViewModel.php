<?php

namespace Application\User\ViewModels;

use Support\Traits\apiResponse;
use Domain\User\Resources\UserResource;
use League\Fractal\Serializer\JsonApiSerializer;
use Application\User\Transformers\UserTransformer;
use Domain\User\Resources\Contracts\UserResourceInterface;


class UserViewModel
{
    use apiResponse;
    public function __construct(private UserResourceInterface $resource) {}

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

        return  fractal()->item($this->resource->getData()['user'] ?? $this->resource->getData())
            ->transformWith(new UserTransformer())
            ->serializeWith(new JsonApiSerializer())
            ->addMeta(['token_type' => 'Bearer', 'token' => $this->resource->getData()['token'] ?? null, 'success' => $this->resource->isSuccess() ?? null, 'code' => $this->resource->getCode()])
            ->toArray();
    }
}
