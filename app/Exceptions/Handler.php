<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use MrCrankHank\ConsoleAccess\Exceptions\ConnectionNotPossibleException;
use MrCrankHank\ConsoleAccess\Exceptions\PublicKeyMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \App\Exceptions\ErrorException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        if (
            $exception instanceof ErrorException or
            $exception instanceof ConnectionNotPossibleException or
            $exception instanceof PublicKeyMismatchException
        )
        {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $exception->getMessage(),
                    'data' => [],
                ], 500);
            }
        }

        return parent::render($request, $exception);
    }
}
