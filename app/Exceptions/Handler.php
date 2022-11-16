<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response|Application|ResponseFactory
    {

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Resource not found',
            ], 404);
        }
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 400);
        }
        if (in_array($e->getCode(), [500, 503])) {
            return response()->json([
                'message' => 'An error occurs please try again later'
            ], 406);
        }
        if ($e instanceof AuthenticationException) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        }
        if ($e instanceof AccessDeniedHttpException) {
            return response()->json([
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        }
        if ($e instanceof ThrottleRequestsException) {
            return response()->json([
                'message' => 'Too Many attempts. Please try again later',
            ], 429);
        }
        if ($e instanceof MethodNotAllowedException) {
            return response([
                'message' => 'Method not allowed'
            ], 405);
        }

        return parent::render($request, $e);
    }
}
