<?php

declare(strict_types=1);

function operational_update_guide(): array
{
    return [
        'title' => 'Guia de Atualizacao Operacional',
        'description' => 'Procedimento para atualizar os dados publicos da reuniao sem editar multiplas copias.',
        'single_source' => 'app/data/meeting.php',
        'steps' => [
            ['order' => 1, 'action' => 'Abra app/data/meeting.php na raiz do projeto.'],
            ['order' => 2, 'action' => 'Altere apenas o(s) campo(s) necessario(s): join_url, meeting_id, password, type, starts_at, ends_at, status, status_override.'],
            ['order' => 3, 'action' => 'Salve o arquivo e faca deploy (commit + push / upload via FTP).'],
            ['order' => 4, 'action' => 'Abra a home publica e confirme que o card da reuniao reflete os dados atualizados.'],
            ['order' => 5, 'action' => 'Se houver duvida, execute php tests/run.php para validar o contrato antes de publicar.'],
        ],
        'rule' => 'Nenhuma informacao de reuniao deve ser editada fora de app/data/meeting.php.',
        'warning' => 'O MVP nao possui /admin. Qualquer atualizacao editorial segue este mesmo fluxo manual versionado.',
    ];
}

function verify_update_flow(array $contract): array
{
    $checklist = [];

    $meeting = $contract['meeting'] ?? [];
    if (!is_array($meeting)) {
        $meeting = [];
    }

    $requiredKeys = [
        'meeting.join_url' => $meeting['join_url'] ?? null,
        'meeting.meeting_id' => $meeting['meeting_id'] ?? null,
        'meeting.password' => $meeting['password'] ?? null,
        'meeting.type' => $meeting['type'] ?? null,
        'meeting.starts_at' => $meeting['starts_at'] ?? null,
        'meeting.ends_at' => $meeting['ends_at'] ?? null,
        'meeting.updated_at' => $meeting['updated_at'] ?? null,
    ];

    foreach ($requiredKeys as $label => $value) {
        $checklist[] = [
            'field' => $label,
            'present' => is_string($value) && trim($value) !== '',
            'message' => (is_string($value) && trim($value) !== '')
                ? "Campo '{$label}' preenchido."
                : "Campo '{$label}' ausente ou vazio.",
        ];
    }

    return [
        'title' => 'Verificacao do Fluxo de Atualizacao',
        'passed' => !in_array(false, array_column($checklist, 'present'), true),
        'checks' => $checklist,
        'note' => 'Se todos os campos estao preenchidos, o fluxo da fonte unica esta integro.',
    ];
}

function editorial_rules(): array
{
    return [
        ['id' => 'EDT-01', 'rule' => 'Creditos publicos usam apenas primeiro nome ou apelido.', 'applies_to' => 'creditos, rodape, agradecimentos'],
        ['id' => 'EDT-02', 'rule' => 'Nenhuma referencia a sobrenome, endereco, telefone ou e-mail pessoal de membro.', 'applies_to' => 'todo conteudo publico'],
        ['id' => 'EDT-03', 'rule' => 'Atualizacoes editoriais seguem o fluxo manual da fonte unica: editar, verificar, publicar.', 'applies_to' => 'processo editorial'],
        ['id' => 'EDT-04', 'rule' => 'A unica origem autorizada para dados da reuniao e app/data/meeting.php.', 'applies_to' => 'operacao'],
        ['id' => 'EDT-05', 'rule' => 'Conteudo institucional reside em app/content/home.php e pode ser alterado pelo mesmo fluxo manual.', 'applies_to' => 'conteudo'],
    ];
}

function validate_editorial_credits(string $creditText): array
{
    $trimmed = trim($creditText);
    $issues = [];

    if ($trimmed === '') {
        return [
            'valid' => true,
            'issues' => [],
            'message' => 'Nenhum credito textual encontrado — sem violacoes.',
        ];
    }

    $words = preg_split('/\s+/u', $trimmed, -1, PREG_SPLIT_NO_EMPTY);

    if ($words === false) {
        return [
            'valid' => false,
            'issues' => ['EDT-00: Falha ao processar o texto do credito — codificacao invalida.'],
            'message' => 'Nao foi possivel analisar o texto do credito.',
        ];
    }

    if (count($words) < 2) {
        return [
            'valid' => true,
            'issues' => [],
            'message' => 'Credito com palavra unica — compativel com apelido ou primeiro nome.',
        ];
    }

    if (count($words) >= 3) {
        $issues[] = 'EDT-01: Credito com tres ou mais palavras — possivel nome completo. Use apenas primeiro nome ou apelido.';
    }

    $surnamePatterns = [
        '/\b(?:dos?\s+)?(?:Silva|Santos|Oliveira|Souza|Lima|Pereira|Costa|Ferreira|Rodrigues|Almeida|Nascimento|Araujo|Barbosa|Cardoso|Carvalho|Castro|Correia|Dias|Gomes|Goncalves|Martins|Melo|Monteiro|Moreira|Nunes|Ramos|Ribeiro|Rocha|Teixeira|Xavier)\b/ui',
    ];

    foreach ($surnamePatterns as $pattern) {
        if (preg_match($pattern, $creditText)) {
            $issues[] = 'EDT-02: Sobrenome comum detectado no credito. Substitua por primeiro nome ou apelido.';
            break;
        }
    }

    if (preg_match('/\b\d{2,}\b/', $creditText)) {
        $issues[] = 'EDT-02: Sequencia numerica detectada no credito — possivel telefone ou documento.';
    }

    if (preg_match('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i', $creditText)) {
        $issues[] = 'EDT-02: Endereco de e-mail detectado no credito. Remova antes de publicar.';
    }

    return [
        'valid' => $issues === [],
        'issues' => $issues,
        'message' => $issues === []
            ? 'Credito editorial em conformidade com as regras do MVP.'
            : 'Creditos com violacoes detectadas — revise antes de publicar.',
    ];
}

function anonymity_checklist(): array
{
    return [
        'title' => 'Checklist de Anonimato para Publicacao',
        'version' => 'MVP v1',
        'items' => [
            ['id' => 'ANON-01', 'check' => 'Nenhum nome completo de membro esta presente no conteudo publico.', 'severity' => 'blocker'],
            ['id' => 'ANON-02', 'check' => 'Nenhuma foto identificavel de membro esta referenciada ou embutida no HTML.', 'severity' => 'blocker'],
            ['id' => 'ANON-03', 'check' => 'Nenhum depoimento com detalhes identificaveis foi publicado.', 'severity' => 'blocker'],
            ['id' => 'ANON-04', 'check' => 'Textos mencionam apenas "grupo", "membros", "companheiros" — nunca nomes pessoais.', 'severity' => 'warning'],
            ['id' => 'ANON-05', 'check' => 'Links externos apontam apenas para recursos oficiais de NA ou aprovados pelo grupo.', 'severity' => 'warning'],
        ],
    ];
}

function scan_content_block(array $block, string $blockLabel = ''): array
{
    $findings = [];
    $text = '';

    foreach ($block as $value) {
        if (is_string($value)) {
            $text .= ' ' . $value;
        }
    }

    $text = trim($text);

    if ($text === '') {
        return [
            'block' => $blockLabel,
            'passed' => true,
            'findings' => [],
            'message' => 'Bloco vazio — sem violacoes.',
        ];
    }

    $context = $blockLabel !== '' ? "bloco '{$blockLabel}'" : 'bloco anonimo';

    if (preg_match_all('/\b[A-Z][a-zà-ú]+(?:\s+(?:d[aeo]s?\s+)?[A-Z][a-zà-ú]+)+\b/u', $text, $matches)) {
        foreach ($matches[0] as $match) {
            $matchTrimmed = trim($match);
            if (iconv_strlen($matchTrimmed, 'UTF-8') > 20 && str_word_count($matchTrimmed) >= 2) {
                $findings[] = "ANON-01: Possivel nome completo em {$context}: '{$matchTrimmed}'.";
            }
        }
    }

    if (preg_match('/\bfotos?\b|\bimagens?\b|\bretratos?\b/ui', $text)) {
        $findings[] = "ANON-02: Referencia a foto ou imagem em {$context}.";
    }

    if (preg_match('/\b(?:eu|minha|meu)\s+(?:historia|recuperacao|caminhada|experiencia)\b/ui', $text)) {
        $findings[] = "ANON-03: Possivel depoimento pessoal em {$context}.";
    }

    if (preg_match('/\b[A-Z][a-zà-ú]+\s+(?:disse|relatou|contou|compartilhou|falou)\b/u', $text)) {
        $findings[] = "ANON-04: Citacao com nome pessoal em {$context}.";
    }

    return [
        'block' => $blockLabel,
        'passed' => $findings === [],
        'findings' => $findings,
        'message' => $findings === []
            ? "Bloco '{$blockLabel}' passou no checklist de anonimato."
            : "Bloco '{$blockLabel}' requer revisao antes de publicar.",
    ];
}

function run_full_anonymity_check(array $contentBlocks): array
{
    $results = [];

    foreach ($contentBlocks as $label => $block) {
        $results[] = scan_content_block(is_array($block) ? $block : [$block], $label);
    }

    $allPassed = !in_array(false, array_column($results, 'passed'), true);

    return [
        'passed' => $allPassed,
        'summary' => $allPassed
            ? 'Todos os blocos passaram no checklist de anonimato.'
            : 'Alguns blocos precisam de revisao antes da publicacao.',
        'blocks' => $results,
        'checklist' => anonymity_checklist()['items'],
    ];
}

function validate_content_against_rules(array $homeContent): array
{
    $rules = editorial_rules();
    $violations = [];
    $creditKeyPatterns = ['/credits?/', '/responsaveis?/', '/autor/', '/assinatura/'];

    foreach ($homeContent as $sectionKey => $section) {
        if (!is_array($section)) {
            continue;
        }

        foreach ($section as $field => $value) {
            if (!is_string($value) || trim($value) === '') {
                continue;
            }

            $isCreditField = false;

            foreach ($creditKeyPatterns as $pattern) {
                if (preg_match($pattern, (string) $field)) {
                    $isCreditField = true;
                    break;
                }
            }

            if ($isCreditField) {
                $creditCheck = validate_editorial_credits($value);

                if (!$creditCheck['valid']) {
                    foreach ($creditCheck['issues'] as $issue) {
                        $violations[] = "home_content.{$sectionKey}.{$field}: {$issue}";
                    }
                }
            }

            if (preg_match('/\b\d{8,}\b/', $value)) {
                $violations[] = "home_content.{$sectionKey}.{$field}: EDT-02 — Possivel telefone no conteudo.";
            }

            if (preg_match('/\b\d{3}\.\d{3}\.\d{3}-\d{2}\b/', $value)) {
                $violations[] = "home_content.{$sectionKey}.{$field}: EDT-02 — Possivel CPF no conteudo.";
            }
        }
    }

    return [
        'valid' => $violations === [],
        'rules_count' => count($rules),
        'violations' => $violations,
        'rules' => $rules,
    ];
}
