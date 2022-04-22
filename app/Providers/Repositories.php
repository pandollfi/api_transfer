<?php

namespace App\Providers;

use App\Interfaces\Repository\PaymentOrdersRepository;
use App\Repositories\PaymentOrdersRepositoryImpl;
use Illuminate\Support\ServiceProvider;

class Repositories extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PaymentOrdersRepository::class, PaymentOrdersRepositoryImpl::class);
    }
}
