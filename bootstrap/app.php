<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Append RLS context middleware to web group (runs on every web request)
        $middleware->appendToGroup('web', \App\Http\Middleware\SetRlsContext::class);
        
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'company' => \App\Http\Middleware\EnsureCompanySelected::class,
            'rls' => \App\Http\Middleware\SetRlsContext::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
