<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//API
$router->post('paymentOrders', ['uses' => 'PaymentOrdersController@index', 'as' => 'payment']);
$router->get('paymentOrders/{internalId}', ['uses' => 'PaymentOrdersController@find', 'as' => 'payment/find']);

