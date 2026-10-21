<?php

use App\Http\Middleware\UsuarioAtivo;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        App\Console\Commands\CriarUsuario::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['usuario.ativo' => UsuarioAtivo::class]);
        $middleware->redirectGuestsTo(fn () => route('entrar.form'));
        $middleware->redirectUsersTo(fn () => route('admin.inicio'));

        $proxies = array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('TRUSTED_PROXIES', '')),
        )));
        if ($proxies !== []) {
            $middleware->trustProxies(at: $proxies);
        }
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Não expor dados sensíveis; configurações detalhadas virão com as Stories.
    })->create();
