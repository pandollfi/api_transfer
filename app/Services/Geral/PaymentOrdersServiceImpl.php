<?php

namespace App\Services\Geral;

use App\DTO\RetornoDTO;
use App\Interfaces\Service\PaymentOrdersService;
use App\Interfaces\Repository\PaymentOrdersRepository;
use App\Models\PaymentOrders;

class PaymentOrdersServiceImpl implements PaymentOrdersService
{
    protected $DTO;
    protected $dataAtual;
    private $repository;

    public function __construct
    (
        PaymentOrdersRepository $repository
    )
    {
        $this->DTO = new RetornoDTO();
        $this->dataAtual = date('Y-m-d');
        $this->repository = $repository;
    }

    public function transferPayment(\stdClass $data): RetornoDTO
    {
        return $this->repository->transferPayment($data);
    }


    public function findTransfer(int $data): PaymentOrders
    {
        return $this->repository->findTransfer($data);
    }

}

