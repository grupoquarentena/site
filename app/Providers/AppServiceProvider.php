<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bindings de domínio serão acrescentados conforme as Stories.
    }

    public function boot(): void
    {
        // Sem permissões globais implícitas.
    }
}
