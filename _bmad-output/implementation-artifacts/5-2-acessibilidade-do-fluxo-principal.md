# Story 5.2: Acessibilidade do Fluxo Principal

Status: done

## Resumo

Adiciona `role=main`, `aria-label`, `lang` explicito, skip link, viewport e foco visivel para o fluxo principal.

## Atualizacao 2026-05-27

- `app/views/home.php` agora usa labels em PT-BR acentuado para `main`, header e footer.
- `public/assets/css/home.css` passou a ter regra global de `:focus-visible`, reduzindo dependencia de estilos pontuais por componente.
- `app/views/partials/support_links.php` ganhou texto `.sr-only` para comunicar links externos a leitores de tela.
- `tmp/index-old.html` e `tmp/index-new.html` foram gerados com CSS inline para revisao visual local sem PHP instalado.

## Verificacao

- `php tests/run.php` retornou `PASS: 225 verificacoes` apos os ajustes.

Ref: NFR11, NFR12, UX-DR11
