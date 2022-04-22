<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Interfaces\Service\PaymentOrdersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentOrdersController extends Controller
{
    private $paymentService;

    public function __construct(PaymentOrdersService $paymentService)
    {
        $this->paymentService =  $paymentService;
    }

    public function index(Request $request) : JsonResponse
    {
        Log::info('Controller: PaymentOrders. Object received from Application Service: ', ['Object' => $request->all()]);
        $this->validate($request,
            [
                'externalId' => 'required',
                'amount' => 'required',
                'expectedOn' => 'required'
            ]
        );
        $data = new \stdClass();
        $data->externalId = $request->input('externalId');
        $data->amount = $request->input('amount');
        $data->expectedOn = $request->input('expectedOn');
        $data->dueDate = $request->input('dueDate') ?? null;
        $result = $this->paymentService->transferPayment($data);

        Log::info('Controller: PaymentOrders. Object returned to Application Service: ', ['Object' => $result->retornarArray()]);
        return \response()->json(
            $result->retornarArray(),
            $result->getStatusCode(),
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function find(Request $request, int $internalId) : JsonResponse
    {
        Log::info('Controller: PaymentOrders. Object received from Application Service: ', ['Object' => $request->all()]);

        $result = $this->paymentService->findTransfer($internalId);

        return \response()->json(
            $result,
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }
}
