<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <title>@yield('title', 'Grupo QuarenteNA')</title>
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>
    <a class="pular" href="#conteudo">Ir para o conteúdo</a>
    <header><div class="container topo"><a href="{{ route('publico.inicio') }}">Grupo QuarenteNA</a><nav aria-label="Navegação"><a href="{{ route('publico.inicio') }}">Início</a>@auth <a href="{{ route('admin.inicio') }}">Minha área</a>@else <a href="{{ route('entrar.form') }}">Entrar</a>@endauth</nav></div></header>
    <main id="conteudo" class="container">@yield('content')</main>
    <footer class="container"><p>Grupo QuarenteNA — acolhimento, serviço e anonimato.</p></footer>
</body>
</html>
