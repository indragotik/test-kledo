<?php

use App\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Exceptions\MissingAbilityException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware configured in app/Http/Kernel.php
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $exception, $request) {
            if (! $request->expectsJson() && ! $request->is('api/*')) {
                return null;
            }

            $message = $request->bearerToken()
                ? 'Authentication token is invalid, inactive, or expired'
                : 'Authentication token is missing';

            return ApiResponse::unauthorized($message);
        });

        $exceptions->render(function (MissingAbilityException $exception, $request) {
            if (! $request->expectsJson() && ! $request->is('api/*')) {
                return null;
            }

            return ApiResponse::forbidden('Token does not have the required ability');
        });

        $exceptions->render(function (ValidationException $exception, $request) {
            if (! $request->expectsJson() && ! $request->is('api/*')) {
                return null;
            }

            return ApiResponse::validationError($exception->errors(), 'Validation error');
        });
    })->create();
