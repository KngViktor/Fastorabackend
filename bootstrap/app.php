<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Caches successful GET responses on the public content API. Appended to
        // the api group so it wraps every /api route without each one opting in.
        // Invalidated by the model observers, which already fire on save.
        $middleware->appendToGroup('api', \App\Http\Middleware\CacheApiResponse::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
