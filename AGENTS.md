# Agentes — Grupo QuarenteNA

- Idioma `pt-BR`; fuso `America/Bahia`. Aplicação e governança no repositório `grupoquarentena/site`.
- **Git e revisões:** seguir [`docs/git-workflow-bmad.md`](docs/git-workflow-bmad.md). `main` é padrão/congelada; desenvolvimento em branch de `dev` via Issue e PR; merge só com aprovação humana.
- **Fontes normativas:** `_bmad-output/planning-artifacts/{ProductBrief,PRD,UX-Design,Arquitetura}.md`; `QuarenteNA.md` é inventário AS-IS. Consultar apenas trechos necessários da tarefa.
- **BMAD 6.12.4** já instalado: não reinstalar; Epics/Stories somente após integrar a fundação. **Laravel Boost** orienta implementação via runtime Docker, sem substituir requisitos.
- **Backlog B:** `_bmad-output/planning-artifacts/epics.md` é índice/estado global; `_bmad-output/planning-artifacts/epics/epic-NN.md` é fonte exclusiva das Stories/AC/gates daquele Epic. Seguir o contrato e a entrada local `quarentena-backlog` em [`docs/git-workflow-bmad.md`, §4.1](docs/git-workflow-bmad.md#backlog-b). Nunca anexar Stories ao índice ou recriar arquivo existente. Help/agentes consultam índice + Epic pertinente; aprovação condicionada não autoriza build.
- **Menos artefatos:** não gerar relatórios/ADR/documentos por Story; manter backlog distribuído sem cópia monolítica e arquivos operacionais exigidos pelo BMAD. Atualizar documentos existentes quando mudar requisitos, UX ou arquitetura; resumir mudanças relevantes no `CHANGELOG.md` único do projeto.
- Pessoas existem sem login; cada `usuario` humano pertence a uma `pessoa` (`pessoa_id NOT NULL UNIQUE`). Sem conta root compartilhada, perfis comerciais, permissões implícitas ou usuário fictício para webhook.
- Respeitar anonimato, segregação de kit/tesouraria, encargos vigentes e PII mínima; testar acessos negados. Não antecipar os demais módulos antes das Stories.
- Não versionar segredos, `.env`, logs, dados reais, backups nem `tmp/`. Limpar temporários da própria tarefa após uso, sem apagar trabalhos alheios.
- Validar testes/CI e diff completo antes do review independente; `review` até aprovação/merge, `done` depois. Nunca afirmar testes que não foram executados.
