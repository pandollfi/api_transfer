<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Lista com as arrays de retorno para cada exceção personalizada.
     *
     * @var array
     */

    protected $retornoErrorException = [
        'message' => "Failed Operation",
        'error' => 500
    ];


    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ( $exception instanceof \ErrorException)
        {
            return response()->json(
                $this->retornoErrorException,
                500,
                [],
                JSON_UNESCAPED_UNICODE
            );
        }

        if ( $exception instanceof ConnectionException)
        {
            return response()->json(
                $this->retornoConnectionException,
                504,
                [],
                JSON_UNESCAPED_UNICODE
            );
        }
        return parent::render($request, $exception);
    }
}
