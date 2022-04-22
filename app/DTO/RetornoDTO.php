<?php

namespace App\DTO;

class RetornoDTO
{
    protected $statusCode;
    protected $internalId;
    protected $status;

    public function __construct()
    {
        $this->setStatusCode(500);
        $this->setInternalId('Erro desconhecido');
        $this->setStatus(111);
    }

    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    public function getInternalId() : string
    {
        return $this->internalId;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatusCode(int $statusCode) : void
    {
        $this->statusCode = $statusCode;
    }

    public function setInternalId(string $internalId) : void
    {
        $this->internalId = $internalId;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function retornarArray() : array
    {
        return [
            'internalId' => $this->getInternalId(),
            'status' => $this->getStatus()
        ];
    }
}
