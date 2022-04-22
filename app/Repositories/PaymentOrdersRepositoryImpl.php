<?php

namespace App\Repositories;

use App\DTO\RetornoDTO;
use App\Interfaces\Repository\PaymentOrdersRepository;
use App\Models\PaymentOrders;
use App\Helpers\Helpers;

class PaymentOrdersRepositoryImpl implements PaymentOrdersRepository
{
    private $paymentOrders;
    private $dateNow;
    protected $DTO;

    public function __construct(PaymentOrders $paymentOrders)
    {
        $this->paymentOrders = $paymentOrders;
        $this->DTO = new RetornoDTO();
        $this->dateNow = date("Y-m-d");
    }

    public function transferPayment(\stdClass $data) : ? RetornoDTO
    {
        $payment = new PaymentOrders();
        $payment->externalId = $data->externalId;
        $payment->amount = $data->amount;

        if($data->dueDate){
            if(Helpers::validarData($data->dueDate, 'd-m-Y')){
                $payment->dueDate = Helpers::formatarDataPadrao($data->dueDate);
            }else{
                $message = "Incorrectly formatted dueDate. Enter in format dd-mm-yyyy";
                return $this->messageError($message);
            }
        }

        if(Helpers::validarData($data->expectedOn, 'd-m-Y')){
            $payment->expectedOn = Helpers::formatarDataPadrao($data->expectedOn);
        }else{
            $message = "Incorrectly formatted expectedOn. Enter in format dd-mm-yyyy";
            return $this->messageError($message);
        }

        return $this->validatePaymentOrder($payment);
    }

    public function findTransfer(int $internalId): ? PaymentOrders
    {
        $paymentOrders = new PaymentOrders();
        $find = $this->paymentOrders
            ->setConnection('api')
            ->select('internalId', 'amount', 'expectedOn', 'dueDate', 'externalId', 'status')
            ->where('internalId', $internalId)
            ->first();
        return $find ?? $paymentOrders;
    }

    public function findTransferExternalId(int $externalId): ? PaymentOrders
    {
        return $this->paymentOrders
            ->setConnection('api')
            ->select('internalId', 'amount', 'expectedOn', 'dueDate', 'externalId', 'status')
            ->where('externalId', $externalId)
            ->first();
    }

    public function record(PaymentOrders $paymentOrders) : int
    {
        $paymentOrders
            ->setConnection('api')
            ->save();
        return $paymentOrders->internalId;
    }

    private function validatePaymentOrder(PaymentOrders $payment): RetornoDTO
    {
        /** Validação de Moeda */
        if (strripos($payment->amount, ",") || strripos($payment->amount, ".")) {
            $message = "Invalid value! type in cents";
            return $this->messageError($message);
        }

        /** Verificação de Registro Inserido */
        if ($this->findTransferExternalId($payment->externalId)) {
            $message = "A record with this externalId already exists";
            return $this->messageError($message);
        }

        if ($payment->expectedOn == $this->dateNow) {
            $this->validateDueDateForDateNow($payment);
        } elseif ($payment->expectedOn > $this->dateNow) {
            $this->validateDueDateForFutureDate($payment);
        } else {
            $this->messageReject($payment);
        }
        return $this->DTO;
    }

    private function validateDueDateForDateNow(PaymentOrders $payment): void
    {
        if (isset($payment->dueDate)) {
            if ($payment->dueDate < $this->dateNow) {
                $this->messageReject($payment);
            } else {
                $this->messageApproved($payment);
            }
        } else {
            $this->messageApproved($payment);
        }
    }

    private function validateDueDateForFutureDate(PaymentOrders $payment): void
    {
        if ($payment->dueDate) {
            if (strtotime($payment->dueDate) < strtotime($payment->expectedOn)) {
                $this->messageReject($payment);
            } elseif (strtotime($payment->dueDate) >= strtotime($payment->expectedOn)) {
                $this->messageSheduled($payment);
            }
        } else {
            $this->messageSheduled($payment);
        }
    }

    private function messageReject(PaymentOrders $payment): void
    {
        $payment->status = "REJECTED";
        $this->DTO->setStatusCode(405);
        $this->DTO->setInternalId("Business Error - Transfer Overdue");
        $this->DTO->setStatus('REJECTED');
    }

    private function messageApproved(PaymentOrders $payment): void
    {
        $payment->status = "APPROVED";
        $result = $this->record($payment);
        $this->DTO->setStatusCode(201);
        $this->DTO->setInternalId($result . " | Transfer Created and Approved ");
        $this->DTO->setStatus($payment->status);
    }

    private function messageSheduled(PaymentOrders $payment): void
    {
        $payment->status = "SHEDULED";
        $result = $this->record($payment);
        $this->DTO->setStatusCode(201);
        $this->DTO->setInternalId($result . " | Transfer Created and Sheduled ");
        $this->DTO->setStatus($payment->status);
    }

    private function messageError(string $message): RetornoDTO
    {
        $this->DTO->setStatusCode(500);
        $this->DTO->setInternalId("Internal error in transfer service | {$message}");
        $this->DTO->setStatus('REJECTED');
        return $this->DTO;
    }

}
