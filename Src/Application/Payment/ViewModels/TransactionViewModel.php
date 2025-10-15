<?php


namespace Application\Payment\ViewModels;

use Domain\Payment\Resources\Contracts\PaymentResourceInterface;
use Faker\Provider\ar_EG\Payment;

class TransactionViewModel
{

    public function __construct() {}

    public function toResponse(PaymentResourceInterface $resource): mixed
    {
        return [];
    }
}
