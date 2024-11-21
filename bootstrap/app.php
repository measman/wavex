<?php

use App\Http\Middleware\redirectAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            
        ])
        ->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'redirectAdmin' => redirectAdmin::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->report(function (Exception $e) { 
            $data = [
                'method' => request()->getMethod(),
                'message' => $e->getMessage(),
                'user' => Auth::id(),
                'data' => request()->all(),
            ];
    
            if ($e instanceof ValidationException) {
                $data['errors'] = $e->errors();
            }
    
            Log::channel('daily')->info(json_encode($data, JSON_PRETTY_PRINT));      
        });
    })->create();