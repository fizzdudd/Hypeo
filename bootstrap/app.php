<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Vercel atiende por HTTPS detrás de un proxy: confiamos en sus cabeceras
        // para que las URLs y formularios se generen con https://
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 419 (token CSRF vencido: formulario abierto antes de un login/logout o en otra pestaña):
        // en vez de "Page Expired", volvemos al formulario con un aviso y lo escrito.
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() !== 419 || $request->expectsJson()) {
                return null;
            }

            $destino = auth()->check() ? url()->previous(route('productos.index')) : route('login');

            return redirect($destino)
                ->withInput($request->except(['_token', 'password', 'password_confirmation']))
                ->with('aviso', 'Tu sesión se actualizó. Vuelve a intentarlo.');
        });
    })->create();
