<?php

namespace App\Interfaces\Repository;

use App\DTO\RetornoDTO;
use App\Models\PaymentOrders;

interface PaymentOrdersRepository
{
    public function transferPayment(\stdClass $data) : ? RetornoDTO;
    public function findTransfer(int $internalId) : ? PaymentOrders;
}
