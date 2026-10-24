## Objetivo e rastreabilidade

- **Resumo:** o que mudou e por quê?
- **Issue:** `Refs #` (vincular também manualmente em **Development**)
- **Epic/Story BMAD:**
- **Destino:** [ ] `dev` (normal)  [ ] `main` (promoção/hotfix expressamente autorizado)

## Validação

- [ ] Escopo e critérios de aceite cumpridos; testes aplicáveis com comandos/resultados descritos abaixo.
- [ ] Review técnico por outro modelo/sessão sem contexto prévio; achados relevantes resolvidos.
- [ ] CI conferido **quando existente**; diff completo, inclusive novos arquivos, sem segredos/PII/temporários.
- [ ] Código, migrations, testes e documentos BMAD afetados consistentes; riscos e rollback avaliados se cabível.

**Testes e resultados:**

**Riscos / impactos / pendências:**

## Revisão humana e integração

- [ ] Responsáveis elegíveis conferidos (`@grupoquarentena` e `@cirinho`, quando permitidos).
- [ ] Revisor humano elegível solicitado, **distinto do autor do PR**.
- [ ] Aguardar autorização humana; squash na `dev` com título, **linha em branco e resumo das principais mudanças no corpo do commit**. Sem merge ou deploy automático.
