<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Support\Traits\apiResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    use apiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return $this->errorResponse(__('Your token has expired. Please login again.'), 401);
        } catch (TokenInvalidException $e) {
            return $this->errorResponse(__('Your token is invalid. Please login again.'), 401);
        } catch (JWTException $e) {
            return $this->errorResponse(__('Token not provided or malformed.'), 401);
        }

        return $next($request);
    }
}
