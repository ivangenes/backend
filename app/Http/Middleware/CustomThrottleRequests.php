<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponser;
use Illuminate\Routing\Middleware\ThrottleRequests;


class CustomThrottleRequests extends ThrottleRequests

{
    use ApiResponser;
   protected function buildResponse($key, $maxAttempts)
    {
        $response = $this->errorResponse('Demasiados intentos.', 429);

        $retryAfter = $this->limiter->availableIn($key);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );
    }
}
