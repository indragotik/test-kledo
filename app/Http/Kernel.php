<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string>
     */
    protected $middleware = [
        // Local CORS middleware to avoid external package conflicts
        \App\Http\Middleware\CorsMiddleware::class,
        // Ensure API unauthenticated responses are normalized early for any
        // request that targets `api/*` (covers cases where the `api` group
        // is not automatically applied by the router in this app).
        \App\Http\Middleware\ApiUnauthenticatedResponse::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            // keep minimal web middleware so tests and routing work
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // Normalize unauthenticated responses for API callers so they
            // always receive our ApiResponse JSON shape instead of HTML
            // redirects or the framework default JSON message.
            \App\Http\Middleware\ApiUnauthenticatedResponse::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string>
     */
    protected $routeMiddleware = [
        // Use the framework Authenticate middleware so route-level guards
        // like `auth:sanctum` are handled correctly and throw the
        // AuthenticationException that our Handler and middleware expect.
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        // Normalizes unauthenticated responses for API routes when used
        // as a route middleware alias.
        'api.unauth' => \App\Http\Middleware\ApiUnauthenticatedResponse::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    ];
}
