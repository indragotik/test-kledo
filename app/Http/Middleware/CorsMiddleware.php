<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $config = config('cors');

        $origin = $request->headers->get('Origin') ?? '*';

        $allowedOrigins = $config['allowed_origins'] ?? ['*'];
        $allowOrigin = in_array('*', $allowedOrigins) ? '*' : (in_array($origin, $allowedOrigins) ? $origin : null);

        $headers = [
            'Access-Control-Allow-Origin' => $allowOrigin ?? '',
            'Access-Control-Allow-Methods' => implode(',', $config['allowed_methods'] ?? ['*']),
            'Access-Control-Allow-Headers' => implode(',', $config['allowed_headers'] ?? ['*']),
        ];

        if (! empty($config['exposed_headers'] ?? [])) {
            $headers['Access-Control-Expose-Headers'] = implode(',', $config['exposed_headers']);
        }

        if (($config['supports_credentials'] ?? false) === true) {
            $headers['Access-Control-Allow-Credentials'] = 'true';
        }

        if ($request->getMethod() === 'OPTIONS') {
            return response()->noContent(204)->withHeaders($headers);
        }

        $response = $next($request);

        foreach ($headers as $key => $value) {
            if ($value !== '') {
                $response->headers->set($key, $value);
            }
        }

        return $response;
    }
}
