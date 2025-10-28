<?php

use Support\Traits\apiResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Foundation\Application;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Domain\Payment\Jobs\RetryFailedTransactions;
use Spatie\Permission\Middleware\RoleMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'mjwt.auth' => JwtMiddleware::class,
        ]);
        //
        // })->withSchedule(function (Schedule $schedule): void {
        //     $schedule->call(function () {
        //         RetryFailedTransactions::dispatch();
        //     })->everyFiveMinutes();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // $api = new class {
        //     use apiResponse;
        // };

        // $exceptions->render(function (Throwable $e, $request) use ($api) {
        //     if ($request->expectsJson() || $request->is('api/*') || $request->wantsJson()) {
        //         if ($e instanceof AuthenticationException) {
        //             return $api->errorResponse(message: __('You are not authenticated.'), code: 401);
        //         }
        //         if ($e instanceof TokenExpiredException) {
        //             return $api->errorResponse(message: __('Your token has expired. Please login again.'), code: 401);
        //         }
        //         if ($e instanceof TokenInvalidException) {
        //             return $api->errorResponse(message: __('Your token is invalid. Please login again.'), code: 401);
        //         }
        //         if ($e instanceof JWTException) {
        //             return $api->errorResponse(message: __('There is a problem with your token. Please login again.'), code: 401);
        //         }
        // if ($e instanceof ModelNotFoundException) {
        //     return $api->errorResponse(__('Resource not found.'), 404);
        // }

        // if ($e instanceof NotFoundHttpException) {
        //     return $api->errorResponse(__('Not found.'), 404);
        // }


        // if ($e instanceof AuthorizationException || $e instanceof AccessDeniedHttpException) {
        //     return $api->errorResponse(message: __('You are not authorized to perform this action.'), code: 403);
        // }

        // if ($e instanceof ValidationException) {
        //     return $api->errorResponse(message: $e->errors(), code: 422);
        // }

        // Log::error('Unexpected error: ' . $e->getMessage(), [
        //     'exception' => get_class($e),
        //     'file' => $e->getFile(),
        //     'line' => $e->getLine(),
        //     'trace' => $e->getTraceAsString(),
        //     'user_id' => Auth::id(),
        //     'url' => $request->fullUrl(),
        //     'method' => $request->method()
        // ]);

        // return $api->errorResponse(message: __('Something went wrong.'), code: 500);
        //         }
        //     });
    })->create();
