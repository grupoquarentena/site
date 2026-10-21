<?php

use App\Http\Controllers\AutenticacaoController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'inicio')->name('publico.inicio');

Route::middleware('guest')->group(function (): void {
    Route::get('/entrar', [AutenticacaoController::class, 'form'])->name('entrar.form');
    Route::post('/entrar', [AutenticacaoController::class, 'entrar'])
        ->middleware('throttle:6,1')->name('entrar.enviar');
});

Route::middleware(['auth', 'usuario.ativo'])->group(function (): void {
    Route::view('/admin', 'admin.inicio')->name('admin.inicio');
    Route::post('/sair', [AutenticacaoController::class, 'sair'])->name('sair');
});
