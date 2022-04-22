<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class PaymentOrders extends Eloquent
{
    protected $table = 'transfer';
    protected $primaryKey = 'internalId';
    protected $fillable = [
        'internalId',
        'amount',
        'expectedOn',
        'duaDate',
        'externalId',
        'status'
        ];
    public $timestamps = false;
    /**
     * @var mixed|string
     */
}
