<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminAuth; // ← penting

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias middleware kustom
        $middleware->alias([
            'admin.auth' => AdminAuth::class,
        ]);

        // Kalau nanti kamu punya middleware lain, bisa tambahkan di sini juga
        // misalnya:
        // 'something' => \App\Http\Middleware\Something::class,
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
