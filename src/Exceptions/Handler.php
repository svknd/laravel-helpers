<?php

namespace Svknd\Laravel\Helpers\Exceptions;

use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $validationMessage = null;
    protected $modelNotFoundMessage = null;

    public function render($request, Throwable $e)
    {
        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        } elseif ($e instanceof ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => $this->modelNotFoundMessage ? $this->modelNotFoundMessage :  __($e->getMessage()),
            ], 404);
        } elseif ($e instanceof AuthorizationException) {
            $e = new HttpException(403, $e->getMessage());
        } elseif ($e instanceof ValidationException && $e->getResponse()) {
            return response()->json([
                'success' => false,
                'message' => $this->validationMessage ? $this->validationMessage :  __($e->getMessage()),
                'data' => [
                    'fields' => $e->errors()
                ]
            ], 422);
        } elseif ($e instanceof ModelNotFoundException) {            
            $e = new NotFoundHttpException($e->getMessage(), $e);
        } else {            
            return response()->json([
                'success' => false,
                'message' => config('app.debug') ? $e->getMessage() : __('error.500'),
            ], 500);
        }

        return parent::render($request, $e);
    }
}
