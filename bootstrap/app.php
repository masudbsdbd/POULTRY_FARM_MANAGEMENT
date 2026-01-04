<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\RedirectIfNotAuthenticated;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            Route::namespace('App\Http\Controllers')->group(function () {
                Route::middleware(['web'])
                    ->group(base_path('routes/web.php'));
            });
        }

    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'authenticated' => RedirectIfNotAuthenticated::class,
            'guest' => RedirectIfAuthenticated::class,

            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();


// use Illuminate\Foundation\Application;
// use Illuminate\Foundation\Configuration\Exceptions;
// use Illuminate\Foundation\Configuration\Middleware;

// return Application::configure(basePath: dirname(__DIR__))
//     ->withRouting(
//         web: __DIR__ . '/../routes/web.php',
//         commands: __DIR__ . '/../routes/console.php',
//         health: '/up',
//     )
//     ->withMiddleware(function (Middleware $middleware) {
//         $middleware->alias([
//             'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
//             'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
//             'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class
//         ]);
//     })
//     ->withExceptions(function (Exceptions $exceptions) {
//         //
//     })->create();
