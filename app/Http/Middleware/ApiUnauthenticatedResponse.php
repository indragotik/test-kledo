<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Convert framework default 401 / redirect responses for API routes
 * into our standardized ApiResponse JSON shape.
 */
class ApiUnauthenticatedResponse
{
    /**
     * Handle an incoming request and normalize unauthenticated responses.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! $request->is('api/*') && ! $request->expectsJson()) {
            return $response;
        }

        // Convert redirects (e.g. to login) to JSON.
        if ($response instanceof RedirectResponse && $response->getStatusCode() === HttpResponse::HTTP_FOUND) {
            return ApiResponse::unauthorized('Authentication token is missing');
        }

        $status = method_exists($response, 'getStatusCode') ? $response->getStatusCode() : null;

        if ($status !== HttpResponse::HTTP_UNAUTHORIZED) {
            return $response;
        }

        if ($response instanceof JsonResponse) {
            $payload = $response->getData(true);

            // Already standardized? leave it alone.
            if (isset($payload['status'])) {
                return $response;
            }

            $message = $payload['message'] ?? 'Authentication token is invalid, inactive, or expired';
            return ApiResponse::unauthorized($message);
        }

        return ApiResponse::unauthorized('Authentication token is invalid, inactive, or expired');
    }
}
