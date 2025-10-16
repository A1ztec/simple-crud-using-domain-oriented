<?php

use Support\Traits\apiResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // $api = new class {
        //     use apiResponse;
        // };

        // $exceptions->render(function (Throwable $e, $request) use ($api) {
        //     if ($request->expectsJson() || $request->is('api/*') || $request->wantsJson()) {
        //         if ($e instanceof ModelNotFoundException) {
        //             return $api->errorResponse(__('Resource not found.'), 404);
        //         }

        //         if ($e instanceof NotFoundHttpException) {
        //             return $api->errorResponse(__('Not found.'), 404);
        //         }

        //         if ($e instanceof AuthenticationException) {
        //             return $api->errorResponse(message: __('Unauthenticated.'), code: 401);
        //         }

        //         if ($e instanceof AuthorizationException || $e instanceof AccessDeniedHttpException) {
        //             return $api->errorResponse(message: __('You are not authorized to perform this action.'), code: 403);
        //         }

        //         if ($e instanceof ValidationException) {
        //             return $api->errorResponse(message: $e->errors(), code: 422);
        //         }

        //         Log::error('Unexpected error: ' . $e->getMessage(), [
        //             'exception' => get_class($e),
        //             'file' => $e->getFile(),
        //             'line' => $e->getLine(),
        //             'trace' => $e->getTraceAsString(),
        //             'user_id' => Auth::id(),
        //             'url' => $request->fullUrl(),
        //             'method' => $request->method()
        //         ]);

        //         return $api->errorResponse(message: __('Something went wrong.'), code: 500);
        //     }
        // });
    })->create();
