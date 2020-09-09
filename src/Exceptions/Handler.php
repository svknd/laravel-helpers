<?php

namespace Svknd\Laravel\Helpers\Exceptions;

use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException && $exception->getResponse()) {
            return response()->json([
                'success' => false,
                'status' => 422,
                'message' => $exception->getMessage(),
                'data' => [
                    'fields' => $exception->errors()
                ]
            ], 422);
        }

        return parent::render($request, $exception);
    }
}
