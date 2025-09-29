<?php

namespace Domain\Product\Resources;

class ProductResource
{
    public function __construct(private mixed $data = null, private int $code, private bool $success, private string $message) {}

    public static function success(mixed $data = null, string $message = 'Success', int $code = 200): self
    {
        return new self(data: $data, code: $code, success: true, message: $message);
    }

    public static function error(string $message, int $code = 400, mixed $data = null): self
    {
        return new self(data: $data, code: $code, success: false, message: $message);
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}
