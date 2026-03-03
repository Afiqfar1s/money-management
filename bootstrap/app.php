<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

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
    ->withSchedule(function (Schedule $schedule): void {
        // Daily database backup at midnight (00:00)
        $schedule->command('backup:database')
            ->daily()
            ->at('00:00')
            ->name('daily-database-backup')
            ->withoutOverlapping();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle CSRF token mismatch (expired sessions) - redirect to login
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Session expired. Please login again.'], 419);
            }
            return redirect()->route('login')->with('error', 'Your session has expired. Please login again.');
        });
        
        // Handle unauthenticated users
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login')->with('error', 'Please login to continue.');
        });
    })->create();
