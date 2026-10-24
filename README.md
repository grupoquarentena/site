# Site do Grupo QuarenteNA

Site público e sistema administrativo do **Grupo QuarenteNA**, com relatórios e automações, respeitando o anonimato e os princípios de Narcóticos Anônimos (NA).

- **Domínio previsto:** `https://GrupoQuarenteNA.org`
- **Método:** BMAD 6.12.4. **Implementação:** Laravel Boost e agentes coordenados.
- **Infraestrutura planejada:** Laravel 13 / PHP 8.5, PostgreSQL 17, Docker Compose no desenvolvimento, Docker Swarm e Cloudflare Tunnel na produção.

Referência técnica: [`cirinho/template-laravel-bmad`](https://github.com/cirinho/template-laravel-bmad). O planejamento funcional está em `_bmad-output/planning-artifacts/`.

O backlog aprovado como planejamento condicionado usa [índice global](./_bmad-output/planning-artifacts/epics.md) e detalhes em `epics/epic-NN.md`: 15 Epics, 101 Stories e 483 critérios de aceite. A elaboração documental está encerrada; decisões institucionais, readiness e homologação do runtime/Boost continuam pendentes. Não há autorização de sprint, implementação ou deploy por esse aceite.

## Desenvolvimento e validação da fundação

A fundação foi homologada em checkout no filesystem Linux do WSL, Docker e Compose. PHP e Composer executam nos containers.
O checkout de continuidade escolhido na #35 fica em `C:\backup\Documentos\repositorios\Sites\quarentena` (`/mnt/c/...` no WSL); a homologação histórica do checkout Linux não comprova os mounts, permissões ou Boost nesse caminho. Revalidar o ambiente antes de implementar, sem recriar o clone antigo ou sobrescrever configuração privada por inferência.
Para o ambiente isolado, copie `.env.dev.example` para `.env.dev`, restrinja-o com `chmod 600`,
configure senhas locais fortes e distintas e crie `.env` como link simbólico para `.env.dev`
**somente se não houver `.env` anterior**. Ambos são ignorados; scripts, Compose e Laravel leem o mesmo arquivo.

- `./scripts/instalar.sh --preparar`: build, dependências travadas, auditoria e geração inicial da chave; não inicializa banco.
- `./scripts/testar.sh`: Pint, Larastan e PHPUnit com SQLite em memória; gate rápido recorrente de autenticação e regras básicas.
- `./scripts/testar.sh --postgres`: gate obrigatório para homologar a fundação, antes de releases e em alterações de migrations, constraints, índices, consultas específicas, transações, concorrência ou segurança dependente do banco. Executa a suíte com migrations diretas e novamente com restauração do snapshot, apenas em `postgres_test` efêmero; SQLite não substitui essa evidência.
- `./scripts/instalar.sh --inicializar`: exige confirmação e aplica migrations apenas no PostgreSQL Docker local. Nunca usar `migrate:fresh` no banco persistente.
- `./scripts/banco-atualizar.sh`: gera snapshot e catálogo do PostgreSQL real.
- `./scripts/instalar-boost.sh` e `./scripts/configurar-boost-opencode.sh`: integração local do Boost; para cliente OpenCode v2 no Windows orquestrando Debian, use `--windows-wsl` no segundo script. Configuração existente nunca é sobrescrita.

`docker/stack.dev.yml` é exclusivo de homologação interna no nó CIRO, sem portas publicadas nem Cloudflare.
Exige imagem de produção previamente construída identificada por hash (`QUARENTENA_DEV_IMAGE`) e secrets
próprios `quarentena_dev_swarm_db_password` e `quarentena_dev_swarm_app_key`. O Compose permanece o ambiente principal;
workers e scheduler não são ativados na fundação. A política Git e de revisão é `docs/git-workflow-bmad.md`.

O snapshot inclui apenas estrutura e histórico técnico de migrations, nunca dados de pessoas ou sessões.
O PHPUnit isola o cache de configuração e verifica ambiente, host, banco e driver antes de qualquer migration destrutiva.
Em containers Swarm, comandos via `docker exec` devem chamar `quarentena-entrypoint php artisan ...`
para carregar os Docker secrets; `exec php` não herda as variáveis exportadas pelo processo inicial.
