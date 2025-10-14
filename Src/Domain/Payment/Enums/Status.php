<?php


namespace Domain\Payment\Enums;


enum Status: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case PROCESSING = 'processing';

    case FAILED = 'failed';
}
