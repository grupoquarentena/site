> **Documento histórico do MVP (arquivado em 18/10/2026; nunca publicado).**
> Fluxo Git/BMAD adotado durante a implementação do MVP PHP em 2026, preservado
> como registro de processo. Descreve o projeto antigo (branches `codex/*`,
> squash na antiga `main`); não define as regras do repositório atual.

# Git Workflow Integrado ao BMAD

> Regra mestra: o historico Git deste projeto deve refletir as Stories e artefatos gerados pelo BMAD, especialmente os arquivos em `_bmad-output/implementation-artifacts/`.

## 1. Regras criticas de ambiente

### Windows e PowerShell

1. Todos os arquivos devem ser salvos em UTF-8 sem BOM.
2. Antes de comandos que retornam texto, configurar:

```powershell
$OutputEncoding = [System.Text.Encoding]::UTF8
```

3. Nunca usar `\n` literal em `git commit -m`, `gh issue create` ou comandos similares no Windows.
4. Para mensagens longas, usar arquivo temporario:

```powershell
git commit -F msg_temp.txt
gh issue create --body-file corpo_issue.md
```

5. Nomes de arquivos e branches usam apenas ASCII, com `a-z`, `0-9`, `-` e `_`.

## 2. Estrutura de branches

Nunca trabalhar diretamente em `main`.

### Prefixos padrao

- `feature/` para novas funcionalidades
- `fix/` para correcao de bugs
- `hotfix/` para correcao critica em producao
- `refactor/` para refatoracao sem mudanca funcional
- `chore/` para configuracao, build, CI/CD e dependencias
- `docs/` para documentacao

### Regra adicional para Codex

Quando o branch for criado por Codex, prefixar com `codex/`:

- `codex/feature/1-1-home-oficial`
- `codex/fix/status-pill`
- `codex/docs/atualiza-project-context`

### Regra de naming

- Branch de feature deve preferencialmente conter o ID da Story.
- O slug deve ser curto, em ASCII e coerente com o artefato BMAD.

## 3. Padrao de commits

Idioma: PT-BR.

Formato:

```text
<tipo>(<escopo>): <resumo curto>

<corpo opcional explicando o motivo>

Ref: _bmad-output/implementation-artifacts/<arquivo>.md
```

### Tipos permitidos

- `feat`
- `fix`
- `docs`
- `style`
- `refactor`
- `perf`
- `test`
- `chore`

### Exemplo

```text
feat(home): cria hero SSR com CTA principal

- substitui placeholder atual pela entrada publica real
- conecta CTA ao contrato da reuniao
- prepara base para o card da reuniao

Ref: _bmad-output/implementation-artifacts/1-1-home-oficial-com-cta-primario.md
```

## 4. Ciclo de trabalho integrado

1. Sincronizar a base:

```powershell
git checkout main
git pull origin main
```

2. Ler a Story ativa e conferir arquitetura.
3. Criar branch alinhado ao ID da Story.
4. Criar issue correspondente e comentar em issues relacionadas, quando houver impacto cruzado.
5. Desenvolver com commits granulares.
6. Fazer push frequente:

```powershell
git push -u origin NOME_DA_BRANCH
```

7. Criar PR com corpo via arquivo, nunca inline no Windows.
8. Referenciar no PR:
   - a branch
   - a issue principal
   - o artefato BMAD da Story
9. Antes do merge:
   - comentar no PR
   - fechar ou atualizar a issue de referencia
10. Preferir squash merge.

## 5. Integracao com artefatos BMAD

Antes de codar ou commitar, confirmar:

- a Story ativa foi lida
- `architecture.md` foi revisado
- acceptance criteria estao cobertos
- o codigo continua dentro do escopo autorizado do sprint

Depois do merge:

- atualizar o estado da Story ou do sprint via workflow BMAD apropriado
- registrar qualquer ajuste de escopo pelo agente PM/SM se necessario

## 6. Regras de PR e governanca

- Nao mergear sem checks obrigatorios aprovados.
- Manter historico linear.
- Evitar merge commit na `main`.
- PR deve contar a historia final da Story, nao apenas listar arquivos.

## 7. Comandos uteis

```powershell
git status
git log --oneline --graph --all
git pull origin main --rebase
git restore <arquivo>
git commit --amend --no-edit
```

Use `git commit --amend --no-edit` com cuidado e nunca apos push publico compartilhado.
