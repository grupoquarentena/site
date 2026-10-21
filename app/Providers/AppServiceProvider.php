<?php

namespace App\Providers;

use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bindings de domínio serão acrescentados conforme as Stories.
    }

    public function boot(): void
    {
        // Configuração já carregada; o bootstrap do console precede esse ponto.
        TrustProxies::at(config('app.trusted_proxies', []));
        // Sem permissões globais implícitas.
    }
}
