<?php

namespace Domin\User\Resources;

class UserResource
{
    public function __construct(private mixed $data = null,  private int $code,  private bool $success,  private string $message) {}


    public static function success(mixed $data = null, string $message = 'Success', int $code = 200): self
    {
        return new self(success: true, message: $message, code: $code, data: $data);
    }

    public static function error(string $message, int $code = 400, mixed $data = null): self
    {
        return new self(success: false, message: $message, code: $code, data: $data);
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
