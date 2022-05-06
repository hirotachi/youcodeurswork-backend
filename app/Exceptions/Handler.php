<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson() || $request->is('api/*')) {
            return match (true) {
                $exception instanceof MethodNotAllowedHttpException => response()->json([
                    'error' => 'Method not allowed',
                ], 405),
                $exception instanceof NotFoundHttpException => response()->json([
                    'error' => 'Resource not found',
                ], 404),
                $exception instanceof AuthenticationException => response()->json([
                    'error' => 'Unauthenticated',
                ], 401),
                default => parent::render($request, $exception),
            };
        }

        return parent::render($request, $exception);
    }

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
}
