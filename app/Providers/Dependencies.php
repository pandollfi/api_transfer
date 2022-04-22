<?php

namespace App\Providers;

use App\Interfaces\Service\PaymentOrdersService;
use App\Services\Geral\PaymentOrdersServiceImpl;
use Illuminate\Support\ServiceProvider;

class Dependencies extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PaymentOrdersService::class,PaymentOrdersServiceImpl::class);
    }
}
