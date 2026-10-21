@extends('layouts.base')
@section('title', 'Minha área — Grupo QuarenteNA')
@section('content')
<section class="painel">
    <h1>Minha área</h1>
    <p>Olá, {{ auth()->user()->pessoa->nome_exibicao }}.</p>
    <p>A fundação está ativa. Ferramentas de serviço serão habilitadas somente por encargo vigente nas futuras Stories.</p>
    <form method="POST" action="{{ route('sair') }}">@csrf<button type="submit">Sair</button></form>
</section>
@endsection
