# Fundação Laravel — QuarenteNA

- Aplicar `AGENTS.md` e os contratos de `_bmad-output/planning-artifacts/` antes de gerar código.
- Laravel 13 / PHP 8.5, PostgreSQL 17, Blade server-side e testes PHPUnit; executar CLI pelo Docker (`scripts/`).
- `Pessoa` é cadastro-base mesmo sem login; `Usuario` exige `pessoa_id NOT NULL UNIQUE`. Models Eloquent em `app/Models`; lógica funcional somente quando surgir Story.
- Policies/Gates por encargo **vigente** em cada ação sensível; nenhuma role comercial ou privilégio genérico de `/admin`.
- Alterou banco? Migration + testes PostgreSQL + snapshot/documentação gerados do banco real + conciliar `Arquitetura.md`.
- Boost auxilia com documentação e MCP; não recriar arquivos de governança, relatórios ou artefatos BMAD extras.
