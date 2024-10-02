<?php

use App\Http\Middleware\AutoLoginTestUser;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\StorePreviousUrlNotEdit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [AutoLoginTestUser::class, SetLocale::class]);
        $middleware->alias([
            'store.previous.url.not.edit' => StorePreviousUrlNotEdit::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
