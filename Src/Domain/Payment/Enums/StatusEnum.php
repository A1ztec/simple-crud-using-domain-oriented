<?php


namespace Domain\Payment\Enums;


class StatusEnum
{
    public const PENDING = 'pending';
    public const SUCCESS = 'success';
    public const PROCESSING = 'processing';
    public const FAILED = 'failed';


    public static function stripeStatus($status): string
    {
        return match ($status) {
            'paid' => StatusEnum::SUCCESS,
            'unpaid' => StatusEnum::PENDING,
            'failed' => StatusEnum::FAILED,
            default => StatusEnum::PROCESSING,
        };
    }
}
