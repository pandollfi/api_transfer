<?php

namespace App\Interfaces\Service;

use App\DTO\RetornoDTO;
use App\Models\PaymentOrders;

interface PaymentOrdersService
{
    public function transferPayment(\stdClass $data): RetornoDTO;
    public function findTransfer(int $data): PaymentOrders;
}
