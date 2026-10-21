# Agentes — Grupo QuarenteNA

- Idioma `pt-BR`; fuso `America/Bahia`. Aplicação e governança no repositório `grupoquarentena/site`.
- **Git e revisões:** seguir [`docs/git-workflow-bmad.md`](docs/git-workflow-bmad.md). `main` é padrão/congelada; desenvolvimento em branch de `dev` via Issue e PR; merge só com aprovação humana.
- **Fontes normativas:** `_bmad-output/planning-artifacts/{ProductBrief,PRD,UX-Design,Arquitetura}.md`; `QuarenteNA.md` é inventário AS-IS. Consultar apenas trechos necessários da tarefa.
- **BMAD 6.12.4** já instalado: não reinstalar; Epics/Stories somente após integrar a fundação. **Laravel Boost** orienta implementação via runtime Docker, sem substituir requisitos.
- **Menos artefatos:** não gerar relatórios/ADR/documentos por Story; preferir `epics.md` consolidado e arquivos operacionais exigidos pelo BMAD. Atualizar os documentos existentes no mesmo PR quando mudar requisitos, UX ou arquitetura; resumir mudanças relevantes no `CHANGELOG.md` único do projeto.
- Pessoas existem sem login; cada `usuario` humano pertence a uma `pessoa` (`pessoa_id NOT NULL UNIQUE`). Sem conta root compartilhada, perfis comerciais, permissões implícitas ou usuário fictício para webhook.
- Respeitar anonimato, segregação de kit/tesouraria, encargos vigentes e PII mínima; testar acessos negados. Não antecipar os demais módulos antes das Stories.
- Não versionar segredos, `.env`, logs, dados reais, backups nem `tmp/`. Limpar temporários da própria tarefa após uso, sem apagar trabalhos alheios.
- Validar testes/CI e diff completo antes do review independente; `review` até aprovação/merge, `done` depois. Nunca afirmar testes que não foram executados.
