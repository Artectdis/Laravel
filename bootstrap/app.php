<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Broadcast;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        then: function () {
            Broadcast::routes(['middleware' => ['web', 'auth']]);
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*'); 
        // Register ServerTimingMiddleware to track performance metrics
        $middleware->append(\App\Http\Middleware\ServerTimingMiddleware::class);
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
