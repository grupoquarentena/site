-- ============================================================================
-- ARQUIVO: db.sql
-- ============================================================================
-- 
-- PROPÓSITO:
-- Este arquivo contém todo o schema (estrutura) do banco de dados para o
-- site do Grupo QuarenteNA, incluindo criação do banco, tabelas e dados
-- iniciais (seeds) necessários para o funcionamento.
-- 
-- COMO EXECUTAR:
-- 1. Via linha de comando:
--    mysql -u root -p < db.sql
-- 
-- 2. Via phpMyAdmin:
--    - Acesse phpMyAdmin
--    - Clique em "Importar"
--    - Selecione este arquivo
--    - Clique em "Executar"
-- 
-- 3. Via terminal MySQL:
--    mysql> source /caminho/para/db.sql
-- 
-- IMPORTANTE (ARQUIVAMENTO HISTÓRICO):
-- - Este é o SQL de exemplo do rascunho de 2025; NÃO execute em produção.
-- - Credenciais embutidas e comandos destrutivos foram removidos ou
--   desativados na sanitização; crie usuário e senha no servidor com valores
--   próprios e forneça-os via variáveis de ambiente (ver conexao.php).
-- - O link de reunião original foi substituído por valor não funcional.
-- 
-- @author Grupo QuarenteNA
-- @version 1.0.0
-- ============================================================================

-- ============================================================================
-- SEÇÃO 1: CRIAÇÃO DO BANCO DE DADOS
-- ============================================================================

-- Cria o banco de dados se não existir
-- CHARACTER SET utf8mb4: Suporte completo a Unicode (incluindo emojis)
-- COLLATE utf8mb4_unicode_ci: Ordenação correta para português (case-insensitive)
CREATE DATABASE IF NOT EXISTS `grupoquarentena_db` 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

-- Seleciona o banco de dados para uso
USE `grupoquarentena_db`;

-- ============================================================================
-- SEÇÃO 2: CRIAÇÃO DO USUÁRIO (REMOVIDA NA SANITIZAÇÃO)
-- ============================================================================
-- Os comandos originais criavam um usuário MySQL com senha embutida; a senha
-- demonstrativa foi removida deste arquivo histórico. Use um usuário próprio,
-- criado no servidor com senha própria, e informe as credenciais ao site via
-- variáveis de ambiente (QUARENTENA_DB_HOST, QUARENTENA_DB_NAME,
-- QUARENTENA_DB_USER, QUARENTENA_DB_PASSWORD). Exemplo de forma:

-- CREATE USER IF NOT EXISTS 'usuario_local'@'localhost' IDENTIFIED BY '<senha-do-servidor>';
-- GRANT ALL PRIVILEGES ON grupoquarentena_db.* TO 'usuario_local'@'localhost';
-- FLUSH PRIVILEGES;

-- ============================================================================
-- SEÇÃO 3: CRIAÇÃO DA TABELA DE REUNIÕES
-- ============================================================================

-- Remove a tabela se existir (útil para recriação limpa)
-- CUIDADO: Isso apaga todos os dados! Comente em produção.
-- DROP TABLE IF EXISTS `grupoquarentena_reunioes`;

CREATE TABLE IF NOT EXISTS `grupoquarentena_reunioes` (
    -- =========================================================================
    -- COLUNA: id
    -- =========================================================================
    -- Identificador único da reunião (chave primária)
    -- AUTO_INCREMENT: O MySQL gera automaticamente valores sequenciais
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    -- =========================================================================
    -- COLUNA: dia_semana
    -- =========================================================================
    -- Dia da semana usando convenção PHP/JavaScript:
    -- 0 = Domingo
    -- 1 = Segunda-feira
    -- 2 = Terça-feira
    -- 3 = Quarta-feira
    -- 4 = Quinta-feira
    -- 5 = Sexta-feira
    -- 6 = Sábado
    -- 
    -- TINYINT: Ocupa apenas 1 byte (valores de 0 a 255)
    -- NOT NULL: Campo obrigatório
    `dia_semana` TINYINT UNSIGNED NOT NULL COMMENT '0=Dom, 1=Seg, 2=Ter, 3=Qua, 4=Qui, 5=Sex, 6=Sab',
    
    -- =========================================================================
    -- COLUNA: horario_inicio
    -- =========================================================================
    -- Horário de início da reunião no formato HH:MM:SS
    -- TIME: Tipo específico para horários
    -- DEFAULT '20:00:00': Se não informado, assume 20h
    `horario_inicio` TIME NOT NULL DEFAULT '20:00:00',
    
    -- =========================================================================
    -- COLUNA: horario_fim
    -- =========================================================================
    -- Horário de término da reunião
    `horario_fim` TIME NOT NULL DEFAULT '22:00:00',
    
    -- =========================================================================
    -- COLUNA: tipo
    -- =========================================================================
    -- Tipo/formato da reunião
    -- Exemplos: 'Reunião Aberta', 'Reunião Fechada', 'Estudo de Passos', etc.
    -- VARCHAR(100): Texto variável de até 100 caracteres
    `tipo` VARCHAR(100) NOT NULL,
    
    -- =========================================================================
    -- COLUNA: descricao
    -- =========================================================================
    -- Descrição opcional da reunião (para detalhes extras)
    -- TEXT: Permite textos mais longos
    -- NULL: Campo opcional (pode ficar vazio)
    `descricao` TEXT NULL,
    
    -- =========================================================================
    -- COLUNA: link_zoom
    -- =========================================================================
    -- Link completo para acesso à reunião no Zoom
    -- VARCHAR(500): Espaço suficiente para URLs longas com parâmetros
    `link_zoom` VARCHAR(500) NOT NULL,
    
    -- =========================================================================
    -- COLUNA: ativo
    -- =========================================================================
    -- Flag para indicar se a reunião está ativa
    -- BOOLEAN: Na verdade é TINYINT(1) no MySQL
    -- DEFAULT TRUE: Por padrão, reuniões são criadas como ativas
    `ativo` BOOLEAN NOT NULL DEFAULT TRUE,
    
    -- =========================================================================
    -- COLUNA: created_at
    -- =========================================================================
    -- Data/hora de criação do registro
    -- TIMESTAMP: Armazena data e hora
    -- DEFAULT CURRENT_TIMESTAMP: Preenchido automaticamente na inserção
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- =========================================================================
    -- COLUNA: updated_at
    -- =========================================================================
    -- Data/hora da última atualização
    -- ON UPDATE CURRENT_TIMESTAMP: Atualizado automaticamente em cada UPDATE
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- =========================================================================
    -- ÍNDICES
    -- =========================================================================
    -- Índice no dia_semana para buscas rápidas por dia
    INDEX `idx_dia_semana` (`dia_semana`),
    
    -- Índice composto para buscar reuniões ativas de um dia específico
    INDEX `idx_dia_ativo` (`dia_semana`, `ativo`)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabela de reuniões do Grupo QuarenteNA - Narcóticos Anônimos';

-- ============================================================================
-- SEÇÃO 4: INSERÇÃO DOS DADOS INICIAIS (SEEDS)
-- ============================================================================

-- Rascunho histórico: o link real de reunião foi removido na sanitização.
-- O valor abaixo é propositalmente não funcional e mantém o seed legível.
SET @linkZoom = 'https://example.invalid/reuniao-nao-configurada';

-- ATENÇÃO (arquivo histórico): os comandos destrutivos abaixo foram mantidos
-- apenas como referência e ficam desativados. Este SQL é um exemplo do
-- rascunho de 2025 e não deve ser executado em produção.
-- DELETE FROM `grupoquarentena_reunioes` WHERE 1=1;

-- Reseta o auto_increment para começar do 1 (desativado pelo mesmo motivo)
-- ALTER TABLE `grupoquarentena_reunioes` AUTO_INCREMENT = 1;

-- =========================================================================
-- INSERÇÃO DAS REUNIÕES SEMANAIS
-- =========================================================================
-- Ordem: Domingo (0) até Sábado (6)
-- Todas as reuniões ocorrem das 20:00 às 22:00, horário de Salvador/BA

INSERT INTO `grupoquarentena_reunioes` 
    (`dia_semana`, `horario_inicio`, `horario_fim`, `tipo`, `descricao`, `link_zoom`, `ativo`) 
VALUES
    -- DOMINGO (0) - Reunião Aberta
    -- Reuniões abertas permitem qualquer pessoa participar, incluindo
    -- familiares, amigos e profissionais interessados em conhecer NA
    (0, '20:00:00', '22:00:00', 'Reunião Aberta', 
     'Reunião aberta ao público geral. Familiares e amigos são bem-vindos.', 
     @linkZoom, TRUE),
    
    -- SEGUNDA-FEIRA (1) - Reunião Fechada
    -- Reuniões fechadas são exclusivas para pessoas que se identificam como adictos
    (1, '20:00:00', '22:00:00', 'Reunião Fechada', 
     'Reunião fechada para membros. Apenas para quem se identifica como adicto.', 
     @linkZoom, TRUE),
    
    -- TERÇA-FEIRA (2) - Reunião Fechada
    (2, '20:00:00', '22:00:00', 'Reunião Fechada', 
     'Reunião fechada para membros. Apenas para quem se identifica como adicto.', 
     @linkZoom, TRUE),
    
    -- QUARTA-FEIRA (3) - Reunião Fechada
    (3, '20:00:00', '22:00:00', 'Reunião Fechada', 
     'Reunião fechada para membros. Apenas para quem se identifica como adicto.', 
     @linkZoom, TRUE),
    
    -- QUINTA-FEIRA (4) - Estudo de Passos
    -- Reunião focada no estudo dos 12 Passos de NA
    (4, '20:00:00', '22:00:00', 'Estudo de Passos', 
     'Reunião dedicada ao estudo e discussão dos 12 Passos de Narcóticos Anônimos.', 
     @linkZoom, TRUE),
    
    -- SEXTA-FEIRA (5) - Reunião Temática
    -- Reunião com tema específico definido previamente
    (5, '20:00:00', '22:00:00', 'Reunião Temática', 
     'Reunião com tema específico. O tema é anunciado no início da reunião.', 
     @linkZoom, TRUE),
    
    -- SÁBADO (6) - Reunião Aberta
    (6, '20:00:00', '22:00:00', 'Reunião Aberta', 
     'Reunião aberta ao público geral. Familiares e amigos são bem-vindos.', 
     @linkZoom, TRUE);

-- ============================================================================
-- SEÇÃO 5: VERIFICAÇÃO DOS DADOS
-- ============================================================================

-- Query para verificar se os dados foram inseridos corretamente
-- Descomente para executar manualmente:

-- SELECT 
--     id,
--     CASE dia_semana
--         WHEN 0 THEN 'Domingo'
--         WHEN 1 THEN 'Segunda'
--         WHEN 2 THEN 'Terça'
--         WHEN 3 THEN 'Quarta'
--         WHEN 4 THEN 'Quinta'
--         WHEN 5 THEN 'Sexta'
--         WHEN 6 THEN 'Sábado'
--     END AS dia_nome,
--     horario_inicio,
--     horario_fim,
--     tipo,
--     IF(ativo, 'Sim', 'Não') AS ativo
-- FROM grupoquarentena_reunioes
-- ORDER BY dia_semana;

-- ============================================================================
-- SEÇÃO 6: QUERIES ÚTEIS (Referência)
-- ============================================================================

-- Buscar reunião de hoje:
-- SET @hoje = DAYOFWEEK(NOW()) - 1;  -- MySQL retorna 1-7, ajustamos para 0-6
-- SELECT * FROM grupoquarentena_reunioes WHERE dia_semana = @hoje AND ativo = TRUE;

-- Buscar próxima reunião aberta:
-- SELECT * FROM grupoquarentena_reunioes 
-- WHERE tipo = 'Reunião Aberta' AND ativo = TRUE 
-- ORDER BY dia_semana;

-- Atualizar link do Zoom para todas as reuniões:
-- UPDATE grupoquarentena_reunioes SET link_zoom = 'NOVO_LINK_AQUI';

-- ============================================================================
-- FIM DO ARQUIVO
-- ============================================================================
