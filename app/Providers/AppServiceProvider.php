<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('es');

        // Protege la cuota gratuita de Groq: máx. 10 generaciones por minuto por usuario.
        RateLimiter::for('groq', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip())
                ->response(fn () => back()->withInput()->with('error_ia', [
                    'titulo' => 'Vas muy rápido',
                    'mensaje' => 'Generaste muchas campañas seguidas. Espera un minuto y vuelve a intentarlo.',
                    'tipo' => 'ocupada',
                    'espera' => 60,
                ]));
        });
    }
}
