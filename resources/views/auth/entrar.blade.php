@extends('layouts.base')
@section('title', 'Entrar — Grupo QuarenteNA')
@section('content')
<section class="painel">
    <h1>Acesso pessoal</h1>
    <p>Reservado a pessoas com conta institucional previamente autorizada.</p>
    <form method="POST" action="{{ route('entrar.enviar') }}">
        @csrf
        <label for="email">E-mail</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
        <label for="password">Senha</label><input id="password" name="password" type="password" autocomplete="current-password" required>
        @error('email')<p class="erro" role="alert">{{ $message }}</p>@enderror
        <button type="submit">Entrar</button>
    </form>
</section>
@endsection
