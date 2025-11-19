<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * We customize unauthenticated / token related responses to return JSON
     * using our ApiResponse helper so API consumers don't get redirected
     * to a missing `login` route.
     */
    public function render($request, Throwable $exception)
    {
        if (($request->expectsJson() || $request->is('api/*')) && $exception instanceof AuthenticationException) {
            return $this->buildAuthenticationFailureResponse($request);
        }

        if ($exception instanceof MissingAbilityException) {
            return ApiResponse::forbidden('Token does not have the required ability');
        }

        if ($exception instanceof ValidationException) {
            return ApiResponse::validationError(
                $exception->errors(),
                'Validation error'
            );
        }

        return parent::render($request, $exception);
    }

    /**
     * Prevent redirects for unauthenticated requests — return JSON for API.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->buildAuthenticationFailureResponse($request);
        }

        // Fallback to default behavior for non-API/web requests.
        return redirect()->guest(route('login'));
    }

    /**
     * Build the standardized unauthorized response for API token failures.
     */
    private function buildAuthenticationFailureResponse($request)
    {
        $message = $request->bearerToken()
            ? 'Authentication token is invalid, inactive, or expired'
            : 'Authentication token is missing';

        return ApiResponse::unauthorized($message);
    }
}
