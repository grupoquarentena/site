---
name: quarentena-backlog
description: 'Continuidade explícita do planejamento segmentado do Grupo QuarenteNA: descobrir Stories, retomar passo 03 ou validar o backlog B, preservando checkpoints. Não implementa nem gera sprint.'
---

# Backlog B — adaptador local da Issue #35

Adaptação versionada do projeto, não workflow nativo nem fork do BMAD. Não invocar
o criador distribuído para depois interceptar suas escritas. Esta é uma entrada
explícita alternativa, autorizada para estrutura B; critérios metodológicos são
reutilizados por leitura dos passos realmente instalados.

## Preflight obrigatório

1. Ler `AGENTS.md` e `docs/git-workflow-bmad.md`, especialmente §4.1.
2. Conferir branch, HEAD, working tree e hashes dos arquivos a editar. Preservar
   mudanças preexistentes. Confirmar BMAD 6.12.4 em `_bmad/_config/manifest.yaml`;
   em outra versão, parar e revalidar compatibilidade antes de escrever.
3. Ler YAML e índice em `_bmad-output/planning-artifacts/epics.md`. Exigir
   `layout: index-plus-epic-files`; resolver estado físico, sem usar chat como cópia.
   Se ausente, incoerente ou parsing falhar, parar sem corrigir aprovações sozinho.
4. Identificar intenção: **consulta**, **continuidade do passo 03**, ou **validação**.
   Pedido de homologação/consulta não autoriza criar Story. Em dúvida, perguntar.
   `currentEpic` aponta próximo trabalho, não autorização de escrita.
5. Executar `uv run scripts/validar-backlog.py` somente leitura. Erros bloqueiam
   escrita; avisos e pendências humanas são apresentados, não resolvidos por default.

## Consulta dirigida

Para Story N.M, resolver N para `epics/epic-NN.md`; usar
`uv run scripts/validar-backlog.py --story N.M` para caminho, linhas, ACs e metadados.
Ler a Story física completa e trechos normativos pertinentes. Referências como
2.5 + 4.2 → 4.8 exigem carregar os três trechos, não copiar contratos entre arquivos.
Ausência de arquivo/Story significa pendente/erro, nunca permissão para inventá-lo.

## Continuidade do passo 03 — somente após pedido específico

1. Exigir `currentStep: step-03-create-stories`, `status: draft` e autorização
   explícita para o Epic escolhido; conferir catálogo aprovado e ownership dos RF.
2. Ler integralmente, sem ativar o criador ou seus passos 01/02,
   `.agents/skills/bmad-create-epics-and-stories/steps/step-03-create-stories.md`.
   Aplicar objetivos, valor por incremento, dimensionamento, formato Story/GWT,
   entidades just-in-time, predecessoras disponíveis e checkpoints colaborativos.
   **Mapeamento local explícito:** referências de escrita/append de Stories nesse
   passo passam a `epics/epic-NN.md`; referências de inventário, macro e estado
   continuam no índice. A exigência monolítica de organização é substituída por B,
   não os critérios de qualidade. Não seguir o encaminhamento automático ao passo 04.
3. Ler detalhe existente inteiro antes de editar; se ainda não existir, criar
   somente quando iniciar o Epic autorizado. Conferir novamente hash/existência
   imediatamente antes da escrita; divergência inesperada bloqueia sobrescrita.
4. Carregar contratos globais, RFs/UX/arquitetura pertinentes e somente Stories
   predecessoras/consumidoras necessárias. Gates D1–D7 e demais decisões permanecem
   abertos conforme registro; não converter recomendação técnica em aprovação.
5. Elaborar e revisar Stories individualmente no detalhe, com matriz RF → Story →
   AC e dependências próprias. Não regenerar as 39 Stories nem alterar outros Epics
   sem defeito objetivo/autorização. Somente coordenador edita; revisores leem.
6. Atualizar índice apenas para link, contagens e estado necessários; jamais
   escrever definições de Story ali. Não inserir aprovação sem aceite humano
   específico registrado. Aprovação documental não significa readiness/build.
7. Reexecutar validador, conferir diff inclusive untracked e revisão independente
   individual/global conforme escopo autorizado. Parar no checkpoint humano. Não
   finalizar passo 03 nem iniciar próximo Epic apenas por conclusão do lote.

## Validação — ensaio ou conclusão real são intenções diferentes

Ler `.agents/skills/bmad-create-epics-and-stories/steps/step-04-final-validation.md`
somente quando houver pedido de validação, como checklist metodológico. Para B,
o documento lógico é índice + todos os detalhes existentes. Conferir RF/Story/AC,
semântica, negativos, dependências/ciclos, UX, arquitetura e aprovações; distinguir
inventariado, planejado e implementado por evidência, nunca pelo nome do arquivo.

Em **ensaio/homologação**, não atualizar estado nem oferecer conclusão global.
Epics pendentes e gates abertos impedem declarar o backlog inteiro pronto.
Conclusão real requer pedido separado, todos os Epics completos/aprovados,
validações satisfeitas e confirmação humana final. Não executar o passo 04 nativo
com a expectativa de que seu parser descubra automaticamente os arquivos.

## Entrega e limites

Informar fontes carregadas, alterações/testes efetivos, gates, próximo pendente e
limites. Nunca gerar sprint, spec de desenvolvimento, migrations, commit, push,
PR ou implementação por esta entrada. Para build/readiness/sprint futuros, seguir
os consumidores instalados e o contrato §4.1, com autorização própria.

Ensaio sintético só em raiz temporária explicitamente indicada: copiar o contexto
mínimo necessário, resolver o Epic sintético nessa raiz e escrever apenas ali.
Comparar hashes reais antes/depois. Isso testa aderência instrucional nesta sessão,
não garante obediência futura de qualquer modelo nem isolamento imposto pelo BMAD.
