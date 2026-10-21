# Grupo QuarenteNA — Site v2

Plataforma web pública e administrativa do Grupo QuarenteNA. Planejamento aprovado em `_bmad-output/planning-artifacts/`; implementação Laravel modular, PHP 8.5, PostgreSQL 17 e Docker.

## Instalação local (WSL + Docker Compose)

```bash
cp .env.example .env  # definir DB_PASSWORD e DB_TEST_PASSWORD locais
./scripts/instalar.sh --preparar    # imagem, dependências e APP_KEY; não migra banco
./scripts/instalar.sh --inicializar # PostgreSQL e migrations; exige confirmação
./scripts/testar.sh
./scripts/testar.sh --postgres
```

**Primeira resolução de dependências (obrigatória antes do PR):** este pacote de geração não contém `composer.lock` porque não houve acesso a um resolvedor Composer PHP 8.5 neste ambiente. `--preparar` o gera; revisar e versionar o lockfile no mesmo PR antes de CI/merge. Não executar `--inicializar` em banco existente sem validar as conexões.

Acesso: página pública `/`, login institucional `/entrar`, área pessoal `/admin`. Não existe cadastro público, seeder com usuário padrão, ou tela administrativa de encargos nesta fundação. Provisionamento humano inicial por console, conforme `./scripts/artisan.sh usuario:criar --help` dentro do Docker. Recursos funcionais serão construídos após os Epics/Stories.

**Governança:** [AGENTS.md](AGENTS.md) · [Git workflow](docs/git-workflow-bmad.md) · [Arquitetura](_bmad-output/planning-artifacts/Arquitetura.md). Fonte técnica de referência: [`cirinho/template-laravel-bmad`](https://github.com/cirinho/template-laravel-bmad), adaptada ao QuarenteNA; sem dependência operacional desse repositório.

`main` permanece branch padrão e estável; PRs de trabalho destinam-se à `dev`. Build da imagem não é deploy. O `docker/stack.yml` é preparação e **não foi homologado em produção**.
