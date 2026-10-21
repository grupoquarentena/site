<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AutenticacaoController extends Controller
{
    public function form(): View
    {
        return view('auth.entrar');
    }

    public function entrar(Request $request): RedirectResponse
    {
        $credenciais = $request->validate([
            'email' => ['required', 'email', 'max:254'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt([
            'email' => mb_strtolower(trim($credenciais['email'])),
            'password' => $credenciais['password'],
            'ativo' => true,
        ])) {
            throw ValidationException::withMessages([
                'email' => 'Credenciais inválidas ou conta indisponível.',
            ]);
        }

        if (! $request->user()?->pessoa?->ativo) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Credenciais inválidas ou conta indisponível.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.inicio'));
    }

    public function sair(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('publico.inicio');
    }
}
