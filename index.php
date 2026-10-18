<?php
/**
 * ============================================================================
 * ARQUIVO: index.php
 * ============================================================================
 * 
 * PROPÓSITO:
 * Página principal (Landing Page) do Grupo QuarenteNA. Integra backend PHP
 * para lógica de status das reuniões com frontend moderno usando Tailwind CSS
 * e efeitos Glassmorphism. Otimizado para SEO e performance.
 * 
 * ESTRUTURA:
 * 1. Lógica Backend (PHP): Conexão, cálculo de horários, status.
 * 2. Header (HTML): Meta tags, SEO, imports de CSS/JS.
 * 3. Body/Conteúdo:
 *    - Navbar: Navegação responsiva.
 *    - Hero: Destaque principal, status da reunião, botão de ação.
 *    - Sobre NA: Texto informativo.
 *    - História: Trajetória do grupo.
 *    - Footer: Relatórios e informações finais.
 * 
 * RECURSOS:
 * - PHP 8+
 * - PDO (via conexao.php)
 * - Tailwind CSS (via CDN)
 * - Vanilla JS (mínimo, apenas para interatividade básica)
 * 
 * @author Grupo QuarenteNA
 * @version 1.0.0
 * ============================================================================
 */

// ============================================================================
// 1. LÓGICA DE BACKEND (PHP)
// ============================================================================

// Inclui o arquivo de conexão segura
// O uso de 'require_once' garante que o arquivo seja carregado apenas uma vez
require_once 'conexao.php';

// Configuração de Fuso Horário
// Crucial para garantir que o horário das reuniões esteja correto para todos os usuários
// independentemente de onde estejam acessando. O grupo é da Bahia.
date_default_timezone_set('America/Bahia');

/**
 * @var array $gradeSemanal
 * Define a programação fixa das reuniões como fallback (plano B)
 * Caso o banco de dados não esteja disponível, o site usará estes dados.
 * Chaves: 0 (Domingo) a 6 (Sábado)
 */
$gradeSemanal = [
    0 => ['tipo' => 'Reunião Aberta',        'inicio' => '20:00', 'fim' => '22:00'],
    1 => ['tipo' => 'Reunião Fechada',       'inicio' => '20:00', 'fim' => '22:00'],
    2 => ['tipo' => 'Reunião Fechada',       'inicio' => '20:00', 'fim' => '22:00'],
    3 => ['tipo' => 'Reunião Fechada',       'inicio' => '20:00', 'fim' => '22:00'],
    4 => ['tipo' => 'Estudo de Passos',      'inicio' => '20:00', 'fim' => '22:00'],
    5 => ['tipo' => 'Reunião Temática',      'inicio' => '20:00', 'fim' => '22:00'],
    6 => ['tipo' => 'Reunião Aberta',        'inicio' => '20:00', 'fim' => '22:00'],
];

// Tenta buscar dados atualizados do banco
try {
    $pdo = getPDO();
    if ($pdo) {
        // Busca apenas as colunas necessárias das reuniões ativas
        $stmt = $pdo->query("SELECT dia_semana, tipo, horario_inicio, horario_fim FROM grupoquarentena_reunioes WHERE ativo = 1");
        $reunioesDB = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($reunioesDB) {
            foreach ($reunioesDB as $r) {
                // Atualiza a grade com dados do banco
                // Formata horários para remover segundos (HH:MM:SS -> HH:MM)
                $inicio = substr($r['horario_inicio'], 0, 5);
                $fim    = substr($r['horario_fim'], 0, 5);
                
                $gradeSemanal[$r['dia_semana']] = [
                    'tipo'   => $r['tipo'],
                    'inicio' => $inicio,
                    'fim'    => $fim
                ];
            }
        }
    }
} catch (Exception $e) {
    // Silencia erros de banco para não quebrar a UI
    // O site continua funcionando com o array $gradeSemanal estático
}

// Lógica de determinação do Status Atual
$agora = new DateTime();
$diaSemanaHoje = (int)$agora->format('w'); // 0 a 6

// Cria objetos DateTime para inicio e fim da reunião de hoje
$hojeInicio = new DateTime($gradeSemanal[$diaSemanaHoje]['inicio']);
$hojeFim    = new DateTime($gradeSemanal[$diaSemanaHoje]['fim']);

// Variáveis de view
$status = '';
$detalheStatus = '';
$cssStatus = '';
$proximaReuniao = null;

// Verifica em qual situação estamos:
if ($agora >= $hojeInicio && $agora <= $hojeFim) {
    // CASO 1: Dentro do horário da grade semanal (rascunho arquivado)
    $status = 'Horário da Grade Semanal';
    $detalheStatus = 'Rascunho histórico: nenhuma sala é aberta por este arquivo.';
    $cssStatus = 'text-yellow-400 font-semibold';
    $proximaReuniao = null; // Não exibe schema de próxima se já está rolando

} elseif ($agora < $hojeInicio) {
    // CASO 2: Antes da reunião de HOJE
    $status = 'Próxima Reunião: Hoje';
    $detalheStatus = 'Às ' . $gradeSemanal[$diaSemanaHoje]['inicio'] . ' - ' . $gradeSemanal[$diaSemanaHoje]['tipo'];
    $cssStatus = 'text-yellow-400 font-semibold';
    $proximaReuniao = $hojeInicio;

} else {
    // CASO 3: Reunião de hoje já acabou -> Mostrar AMANHÃ
    $amanha = new DateTime('tomorrow');
    $diaSemanaAmanha = (int)$amanha->format('w');
    $status = 'Próxima Reunião: Amanhã';
    $detalheStatus = 'Às ' . $gradeSemanal[$diaSemanaAmanha]['inicio'] . ' - ' . $gradeSemanal[$diaSemanaAmanha]['tipo'];
    $cssStatus = 'text-brand-ice-dark font-semibold text-blue-300'; // Azul claro
    $proximaReuniao = new DateTime('tomorrow ' . $gradeSemanal[$diaSemanaAmanha]['inicio']);
}

// Configuração de URL Canônica (SEO)
// Deve ser a URL final de produção
$canonicalUrl = "https://www.grupoquarentena.com.br/"; 

?>
<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">
<head>
    <!-- 
    ============================================================================
    META TAGS E SEO
    ============================================================================
    -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Título otimizado com localidade -->
    <title>Grupo QuarenteNA — Narcóticos Anônimos Online na Bahia</title> 
    
    <!-- Meta description: Resumo atrativo para clique (CTR) na busca -->
    <meta name="description" content="Grupo QuarenteNA: Reuniões online diárias de Narcóticos Anônimos na Bahia. Apoio anônimo e gratuito para quem tem problemas com drogas. Recuperação é possível.">
    
    <!-- Instruções para robôs de busca (arquivamento histórico: fora do índice) -->
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="keywords" content="drogas, problemas com drogas, narcóticos anônimos, recuperação, anônimos, bahia, reunião online, ajuda, sobriedade, salvador, grupo de apoio">

    <!-- Open Graph / Facebook (Compartilhamento Social) -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:title" content="Grupo QuarenteNA — Narcóticos Anônimos Online">
    <meta property="og:description" content="Recuperação, ajuda e anonimato. Reuniões online todos os dias às 20h. Junte-se a nós se você quer parar de usar drogas.">
    <!-- Imagem de placeholder - idealmente substituir por uma arte real hospedada -->
    <meta property="og:image" content="https://via.placeholder.com/1200x630/0f172a/ffffff?text=Grupo+QuarenteNA+NA">

    <!-- Twitter Cards -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="twitter:title" content="Grupo QuarenteNA — Narcóticos Anônimos Online">
    <meta property="twitter:description" content="Reuniões diárias de recuperação. Gratuito e anônimo.">
    <meta property="twitter:image" content="https://via.placeholder.com/1200x630/0f172a/ffffff?text=Grupo+QuarenteNA+NA">

    <!-- 
    ============================================================================
    ESTILOS E SCRIPTS
    ============================================================================
    -->
    
    <!-- Tailwind CSS (CDN para prototipagem/produção leve) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Configuração personalizada do Tailwind
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ice: {
                            900: '#0B1120', // Fundo principal
                            800: '#151e32', // Elementos secundários
                            400: '#38bdf8', // Destaque principal (Azul)
                            100: '#e0f2fe', // Texto claro
                        },
                        brand: {
                            yellow: '#facc15', // Destaque secundário (Amarelo)
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'], // Fonte moderna limpa
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="estilo.css">

    <!-- 
    ============================================================================
    DADOS ESTRUTURADOS (JSON-LD)
    ============================================================================
    Rascunho histórico: o bloco de evento ativo (que anunciava reuniões e
    carregava o link da sala) foi substituído por dados institucionais
    estáticos. O JSON permanece válido e sem informações operacionais.
    -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Grupo QuarenteNA - Narcóticos Anônimos Bahia",
      "url": "https://www.grupoquarentena.com.br/",
      "description": "Grupo de Narcóticos Anônimos com reuniões online, sediado na Bahia. Registro histórico de rascunho (2025)."
    }
    </script>
</head>

<body class="bg-ice-900 text-ice-100 font-sans antialiased selection:bg-brand-yellow selection:text-ice-900">

    <!-- Link para pular navegação (Acessibilidade) -->
    <a href="#main-content" class="skip-link">Pular para o conteúdo principal</a>

    <!-- 
    ============================================================================
    BACKGROUND DECORATIVO
    ============================================================================
    -->
    <!-- Gradiente de fundo fixo -->
    <div class="fixed inset-0 z-[-1] bg-gradient-to-br from-ice-900 via-[#0f172a] to-[#020617]"></div>
    
    <!-- Padrão (Pattern) sutil de pontos -->
    <div class="fixed inset-0 z-[-1] opacity-20 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9IiNmZmZmZmYiLz48L3N2Zz4=')]"></div>

    <!-- 
    ============================================================================
    HEADER / NAVBAR
    ============================================================================
    -->
    <header class="fixed w-full top-0 z-50 glass border-b border-white/10 transition-all duration-300" id="navbar">
        <div class="container mx-auto px-4 h-16 flex items-center justify-between">
            <!-- Logo area -->
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-ice-400 flex items-center justify-center text-ice-900 font-bold shadow-glow" aria-hidden="true">
                    NA
                </div>
                <span class="text-lg font-bold tracking-wide text-white">Grupo <span class="text-brand-yellow">QuarenteNA</span></span>
            </div>
            
            <!-- Desktop Nav -->
            <nav class="hidden md:flex gap-6 text-sm font-medium" role="navigation" aria-label="Menu principal">
                <a href="#home" class="hover:text-ice-400 transition-colors">Início</a>
                <a href="#sobre" class="hover:text-ice-400 transition-colors">Sobre NA</a>
                <a href="#historia" class="hover:text-ice-400 transition-colors">História</a>
                <a href="#relatorios" class="hover:text-ice-400 transition-colors">Relatórios</a>
            </nav>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden p-2 text-white outline-none focus:ring-2 focus:ring-ice-400 rounded" aria-label="Abrir menu" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
        
        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu" class="hidden md:hidden bg-ice-800/95 backdrop-blur-xl border-t border-white/10 p-4 flex flex-col gap-4 shadow-xl">
             <a href="#home" class="block p-2 hover:bg-white/5 rounded transition-colors text-white font-medium mobile-link">Início</a>
             <a href="#sobre" class="block p-2 hover:bg-white/5 rounded transition-colors text-white font-medium mobile-link">Sobre NA</a>
             <a href="#historia" class="block p-2 hover:bg-white/5 rounded transition-colors text-white font-medium mobile-link">História do Grupo</a>
             <a href="#relatorios" class="block p-2 hover:bg-white/5 rounded transition-colors text-white font-medium mobile-link">Relatórios</a>
        </div>
    </header>

    <!-- Conteúdo Principal -->
    <main class="pt-20" id="main-content">

        <!-- 
        ========================================================================
        HERO SECTION
        ========================================================================
        -->
        <section id="home" class="min-h-[90vh] flex flex-col items-center justify-center container mx-auto px-4 text-center py-12 relative overflow-hidden">
            
            <!-- Orb decorativo (efeito visual atrás do texto) -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-ice-400/10 rounded-full blur-[100px] pointer-events-none fade-in"></div>

            <!-- Título Principal (H1) -->
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight relative z-10 fade-in" style="animation-delay: 0.1s">
                A Recuperação Começa <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-ice-400 to-brand-yellow">Um Dia de Cada Vez</span>
            </h1>

            <!-- Subtítulo -->
            <p class="text-lg md:text-xl text-ice-100/80 max-w-2xl mb-10 leading-relaxed z-10 fade-in" style="animation-delay: 0.2s">
                Se você tem problemas com drogas e quer ajuda, você não está sozinho. 
                O Grupo QuarenteNA oferece apoio gratuito, anônimo e online, direto da Bahia para o mundo.
            </p>

            <!-- CARD DE STATUS DA REUNIÃO (Dinâmico) -->
            <div class="glass p-6 rounded-2xl border border-white/10 w-full max-w-md mb-8 hover:scale-[1.02] transition-transform duration-300 z-10 shadow-lg fade-in" style="animation-delay: 0.3s">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <!-- Indicador visual (bolinha) -->
                    <div class="w-3 h-3 rounded-full <?php echo strpos($status, 'Agora') !== false ? 'bg-green-500 animate-pulse' : 'bg-yellow-500'; ?>"></div>
                    <span class="text-sm uppercase tracking-wider text-ice-100/60 font-medium">Status da Reunião</span>
                </div>
                <!-- Status Texto -->
                <div class="text-2xl <?php echo htmlspecialchars($cssStatus, ENT_QUOTES, 'UTF-8'); ?> mb-1">
                    <?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <p class="text-ice-100/90 font-light"><?php echo htmlspecialchars($detalheStatus, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>

            <!-- ÁREA DO BOTÃO ZOOM (arquivada) -->
            <div class="flex flex-col items-center gap-4 z-10 fade-in" style="animation-delay: 0.4s">
                <!-- Rascunho histórico: referência visual desativada; nenhum link
                     de reunião real é exposto por este arquivo. -->
                <span aria-disabled="true"
                   class="bg-gradient-to-r from-blue-600 to-blue-500 text-white text-xl font-bold py-4 px-10 rounded-full shadow-lg border border-blue-400/30 flex items-center gap-3 opacity-60 cursor-not-allowed select-none">
                    <!-- Ícone de vídeo -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Entrar na Reunião (indisponível)
                </span>

                <!-- Aviso de arquivamento -->
                <div class="text-sm text-ice-100/70 bg-black/30 px-6 py-3 rounded-lg border border-white/5 text-center max-w-md leading-relaxed">
                    Rascunho histórico (2025): os dados de acesso à reunião foram
                    removidos deste arquivo e nenhuma sala é aberta por ele.
                </div>
            </div>
        </section>

        <!-- 
        ========================================================================
        SEÇÃO SOBRE NA
        ========================================================================
        -->
        <section id="sobre" class="py-24 bg-black/20">
            <div class="container mx-auto px-4">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    
                    <!-- Texto Explicativo -->
                    <div class="order-2 md:order-1">
                        <div class="glass p-8 rounded-2xl border border-white/5 card-hover">
                            <h2 class="text-3xl font-bold mb-6 text-brand-yellow">O que é Narcóticos Anônimos?</h2>
                            <p class="mb-4 text-ice-100/80 leading-relaxed">
                                Narcóticos Anônimos (NA) é uma irmandade mundial, sem fins lucrativos, composta por homens e mulheres para quem as drogas se tornaram um problema maior. Somos adictos em recuperação que nos reunimos regularmente para ajudar uns aos outros a nos mantermos limpos.
                            </p>
                            <p class="mb-4 text-ice-100/80 leading-relaxed">
                                O único requisito para ser membro é o desejo de parar de usar. Não há taxas de matrícula, mensalidades ou formulários de inscrição; somos totalmente autofinanciados por nossas próprias contribuições voluntárias.
                            </p>
                            <p class="text-ice-100/80 leading-relaxed">
                                O Grupo QuarenteNA não possui vínculo com nenhum hospital, religião, partido político ou instituição policial. Nosso propósito primordial é levar a mensagem de que qualquer adicto pode parar de usar drogas e encontrar uma nova maneira de viver.
                            </p>
                        </div>
                    </div>

                    <!-- Destaques / Pontos Chave -->
                    <div class="order-1 md:order-2 text-center md:text-left">
                        <h3 class="text-2xl font-bold text-white mb-4">Anonimato e Acolhimento</h3>
                        <p class="text-ice-100/70 mb-8">
                            Em nossas reuniões online, respeitamos seu conforto. Você não é obrigado a ligar a câmera ou o microfone se não quiser. O anonimato é a base espiritual de todas as nossas tradições.
                        </p>
                        
                        <!-- Grid de ícones -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-ice-800/50 p-6 rounded-xl border border-white/5 text-center hover:bg-ice-800/80 transition-colors">
                                <span class="block text-3xl mb-2">🤝</span>
                                <span class="font-semibold text-ice-400 block">Apoio Mútuo</span>
                                <span class="text-xs text-white/50">Um ajuda o outro</span>
                            </div>
                            <div class="bg-ice-800/50 p-6 rounded-xl border border-white/5 text-center hover:bg-ice-800/80 transition-colors">
                                <span class="block text-3xl mb-2">🤐</span>
                                <span class="font-semibold text-ice-400 block">Sigilo Total</span>
                                <span class="text-xs text-white/50">O que é dito fica aqui</span>
                            </div>
                            <div class="bg-ice-800/50 p-6 rounded-xl border border-white/5 text-center hover:bg-ice-800/80 transition-colors">
                                <span class="block text-3xl mb-2">🌍</span>
                                <span class="font-semibold text-ice-400 block">Online</span>
                                <span class="text-xs text-white/50">De qualquer lugar</span>
                            </div>
                            <div class="bg-ice-800/50 p-6 rounded-xl border border-white/5 text-center hover:bg-ice-800/80 transition-colors">
                                <span class="block text-3xl mb-2">🆓</span>
                                <span class="font-semibold text-ice-400 block">Gratuito</span>
                                <span class="text-xs text-white/50">Sem mensalidades</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 
        ========================================================================
        SEÇÃO HISTÓRIA
        ========================================================================
        -->
        <section id="historia" class="py-24 relative overflow-hidden">
            <div class="container mx-auto px-4 max-w-4xl relative z-10">
                <div class="mb-12 text-center">
                    <span class="text-brand-yellow font-semibold tracking-wide uppercase text-sm">Nossa Trajetória</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mt-2">História do Grupo QuarenteNA</h2>
                </div>

                <div class="glass p-8 md:p-12 rounded-3xl border border-white/10 shadow-2xl relative">
                    <!-- Detalhe visual de fundo -->
                    <div class="absolute top-0 right-0 p-32 bg-blue-500/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
                    
                    <div class="space-y-6 text-lg text-ice-100/90 leading-relaxed font-light text-justify md:text-left">
                        <p>
                            O <strong class="text-white">Grupo QuarenteNA</strong> foi fundado em 20 de março de 2020, como uma resposta imediata e vital ao início da pandemia de Covid-19. Com o decreto de lockdown e o fechamento temporário dos grupos presenciais em Salvador, surgiu a necessidade urgente de manter o suporte aos membros.
                        </p>
                        <p>
                            Membros de NA se uniram para criar um espaço seguro no ambiente virtual. Com o apadrinhamento e suporte estrutural do Comitê de Serviço de Área (CSA) Base, o grupo iniciou suas atividades garantindo que a mensagem de recuperação continuasse acessível a todos, mesmo durante o isolamento.
                        </p>
                        <p>
                            O nome "QuarenteNA" homenageia esse período de superação e adaptação. O que nasceu de uma emergência consolidou-se pela força da irmandade, conquistando autonomia e estabelecendo uma estrutura de serviço sólida e acolhedora.
                        </p>
                        <p>
                            Atualmente, mantemos reuniões diárias, todos os dias da semana, das <strong>20h às 22h</strong>. Continuamos crescendo e recebendo novos membros, provando que a recuperação é possível independente do meio.
                        </p>
                        
                        <!-- Destaque para visitantes -->
                        <div class="bg-ice-900/40 p-6 rounded-xl border-l-4 border-brand-yellow mt-8">
                            <p class="italic text-base">
                                "Quem tiver problemas com drogas é bem-vindo em qualquer reunião. Para familiares, amigos, estudantes e profissionais que desejam conhecer o programa sem se identificar como adictos, nossas <strong>Reuniões Abertas</strong> ocorrem aos sábados e domingos."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 
        ========================================================================
        FOOTER / RELATÓRIOS
        ========================================================================
        -->
        <section id="relatorios" class="py-16 border-t border-white/5 bg-black/40">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-start gap-12">
                    
                    <!-- Coluna Esquerda: Sobre -->
                    <div class="md:w-1/3 space-y-4">
                        <h3 class="text-xl font-bold text-white">Grupo QuarenteNA</h3>
                        <p class="text-sm text-ice-100/60 leading-relaxed">
                            Parte integrante da estrutura de serviço de Narcóticos Anônimos da Bahia.
                            Reuniões online diárias, acessíveis e focadas no propósito primordial de levar a mensagem.
                        </p>
                        <div class="text-xs text-ice-100/40 pt-4 border-t border-white/5">
                            <p>&copy; <?php echo date('Y'); ?> Grupo QuarenteNA.</p>
                            <p class="mt-1">Narcóticos Anônimos, NA e a logomarca são marcas registradas.</p>
                        </div>
                    </div>

                    <!-- Coluna Direita: Dashboard Informativo -->
                    <div class="md:w-2/3 w-full">
                        <h4 class="text-lg font-semibold text-ice-400 mb-6 flex items-center gap-2">
                            <!-- Ícone -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                            Quadro de Aviso
                        </h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Card Programação Semanal -->
                            <div class="bg-ice-800/30 border border-white/5 p-5 rounded-xl hover:bg-ice-800/50 transition-colors">
                                <h5 class="font-bold text-white mb-3 text-sm uppercase tracking-wider">Programação Semanal</h5>
                                <ul class="text-sm text-ice-100/70 space-y-2">
                                    <li class="flex justify-between border-b border-white/5 pb-1">
                                        <span>Seg, Ter, Qua</span>
                                        <span class="text-ice-400 font-medium">Fechada</span>
                                    </li>
                                    <li class="flex justify-between border-b border-white/5 pb-1">
                                        <span>Quinta-feira</span>
                                        <span class="text-ice-400 font-medium">Estudo de Passos</span>
                                    </li>
                                    <li class="flex justify-between border-b border-white/5 pb-1">
                                        <span>Sexta-feira</span>
                                        <span class="text-ice-400 font-medium">Temática</span>
                                    </li>
                                    <li class="flex justify-between pt-1">
                                        <span>Sáb e Dom</span>
                                        <span class="text-brand-yellow font-medium">Aberta ao Público</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Card Apoio Extra -->
                            <div class="bg-ice-800/30 border border-white/5 p-5 rounded-xl bg-gradient-to-br from-ice-800/30 to-blue-900/10">
                                <h5 class="font-bold text-white mb-2 text-sm uppercase tracking-wider">Precisa conversar?</h5>
                                <p class="text-sm text-ice-100/70 mb-4 leading-relaxed">
                                    A sala geralmente abre 15 minutos antes e permanece aberta após o encerramento para companheirismo.
                                </p>
                                <span class="inline-block text-xs font-bold text-brand-yellow bg-brand-yellow/10 px-3 py-1.5 rounded-full border border-brand-yellow/20">
                                    Estamos aqui por você
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>
    
    <!-- 
    ============================================================================
    SCRIPTS (JS)
    ============================================================================
    -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Lógica do Menu Mobile
            const menuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileLinks = document.querySelectorAll('.mobile-link');
            let isMenuOpen = false;

            function toggleMenu() {
                isMenuOpen = !isMenuOpen;
                if (isMenuOpen) {
                    mobileMenu.classList.remove('hidden');
                    menuBtn.setAttribute('aria-expanded', 'true');
                } else {
                    mobileMenu.classList.add('hidden');
                    menuBtn.setAttribute('aria-expanded', 'false');
                }
            }

            menuBtn.addEventListener('click', toggleMenu);

            // Fecha o menu ao clicar num link
            mobileLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (isMenuOpen) toggleMenu();
                });
            });

            // Lógica de Scroll e Header
            // Adiciona fundo escuro ao header quando scrollar para baixo
            const header = document.getElementById('navbar');
            
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    header.classList.add('glass-dark', 'shadow-lg');
                    header.classList.remove('glass');
                } else {
                    header.classList.remove('glass-dark', 'shadow-lg');
                    header.classList.add('glass');
                }
            });
        });
    </script>
</body>
</html>
