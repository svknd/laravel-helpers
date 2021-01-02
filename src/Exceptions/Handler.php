<?php

namespace Svknd\Laravel\Helpers\Exceptions;

use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $validationMessage = null;

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException && $exception->getResponse()) {
            return response()->json([
                'success' => false,
                'status' => 422,
                'message' => $this->validationMessage ? $this->validationMessage :  __($exception->getMessage()),
                'data' => [
                    'fields' => $exception->errors()
                ]
            ], 422);
        }

        return parent::render($request, $exception);
    }
}
