# AGENTS.md — Grupo QuarenteNA

## Contexto

- Idioma: português brasileiro.
- Método: BMAD 6.12.4, com OpenCode.
- Utilizar somente os cinco documentos canônicos em `_bmad-output/planning-artifacts/`.
- O MVP antigo não entrou em produção e não representa a arquitetura da nova plataforma.

## Hierarquia de conhecimento

- `QuarenteNA.md`: contexto histórico e funcionamento do legado.
- `ProductBrief.md`: visão, objetivos e escopo estratégico.
- `PRD.md`: requisitos funcionais e regras aprovadas.
- `UX-Design.md`: comportamento e experiência.
- `Arquitetura.md`: decisões técnicas e modelo de dados.
- Código e migrations: implementação executável, quando existirem.

Em caso de divergência, não presumir qual documento está correto: identificar o conflito e propor reconciliação.

## Desenvolvimento

- Respeitar o template Laravel adotado, suas migrations, testes e documentação.
- Utilizar Laravel Boost para consultar APIs, padrões e runtime do Laravel.
- Não duplicar regras de negócio ou fontes de dados.
- Alterações em requisitos devem atualizar o PRD; mudanças arquiteturais devem atualizar a Arquitetura.
- Mudanças no banco exigem migrations, testes e sincronização da documentação do esquema.
- Preservar anonimato, rastreabilidade e controle de acesso por encargo vigente.

## Artefatos e agentes

- Não gerar documentação redundante, versões paralelas ou relatórios permanentes sem necessidade.
- Atualizar arquivos existentes preferencialmente a criar novos.
- Auditorias experimentais devem usar `tmp/`, sem commits.
- Gerar Specs, Stories e artefatos operacionais BMAD somente quando a etapa exigir.
- Utilizar Git para registrar histórico e justificativas, sem replicar changelogs nos documentos.

O desenvolvimento não pode considerar uma Story concluída sem testes e atualização das fontes afetadas.