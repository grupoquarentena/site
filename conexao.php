<?php
/**
 * ============================================================================
 * ARQUIVO: conexao.php
 * ============================================================================
 * 
 * PROPÓSITO:
 * Este arquivo é responsável por estabelecer uma conexão segura com o banco
 * de dados MySQL usando PDO (PHP Data Objects). Utiliza o padrão Singleton
 * para garantir que apenas uma conexão seja criada durante toda a execução
 * do script, economizando recursos do servidor.
 * 
 * SEGURANÇA IMPLEMENTADA:
 * - PDO com ERRMODE_EXCEPTION para capturar erros de forma segura
 * - Charset UTF8MB4 para suporte completo a caracteres especiais e emojis
 * - Prepared Statements habilitados nativamente (emulate_prepares = false)
 * - Timeout de conexão para evitar travamentos
 * 
 * COMO USAR:
 * require_once 'conexao.php';
 * $pdo = getPDO();
 * if ($pdo) {
 *     // Banco conectado, pode usar
 * } else {
 *     // Banco indisponível, usar fallback
 * }
 * 
 * @author Grupo QuarenteNA
 * @version 1.0.0
 * ============================================================================
 */

/**
 * Retorna uma instância única de conexão PDO com o banco de dados.
 * 
 * Esta função implementa o padrão Singleton usando uma variável estática.
 * Na primeira chamada, a conexão é criada e armazenada. Nas chamadas
 * subsequentes, a mesma conexão é reutilizada.
 * 
 * @return PDO|null Retorna objeto PDO se conexão bem-sucedida, null caso contrário
 */
function getPDO(): ?PDO {
    // =========================================================================
    // VARIÁVEL ESTÁTICA - SINGLETON
    // =========================================================================
    // A palavra-chave 'static' faz com que esta variável persista entre
    // chamadas da função. Isso significa que a conexão é criada apenas uma vez.
    static $pdo = null;

    // Se já existe uma conexão, retorna ela imediatamente
    if ($pdo !== null) {
        return $pdo;
    }

    // =========================================================================
    // CONFIGURAÇÕES DO BANCO DE DADOS
    // =========================================================================
    // Rascunho histórico: nenhuma credencial fica embutida neste arquivo.
    // A conexão é configurada exclusivamente por variáveis de ambiente do
    // servidor. Sem configuração válida, getPDO() retorna null de forma
    // controlada e o site usa a agenda estática de fallback (index.php).
    // Nunca commite credenciais reais em repositórios públicos!

    $host    = getenv('QUARENTENA_DB_HOST');
    $db      = getenv('QUARENTENA_DB_NAME');
    $user    = getenv('QUARENTENA_DB_USER');
    $pass    = getenv('QUARENTENA_DB_PASSWORD');
    $charset = 'utf8mb4';                // Charset completo com suporte a emojis

    // Ausência de configuração: falha controlada, sem valores padrão.
    // A senha pode ser vazia se o servidor local for configurado assim,
    // mas precisa ter sido definida explicitamente.
    if (!is_string($host) || $host === ''
        || !is_string($db) || $db === ''
        || !is_string($user) || $user === ''
        || !is_string($pass)) {
        return null;
    }

    // =========================================================================
    // DSN (Data Source Name)
    // =========================================================================
    // O DSN é uma string que contém todas as informações necessárias para
    // o PDO se conectar ao banco de dados.
    $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

    // =========================================================================
    // OPÇÕES DE CONFIGURAÇÃO DO PDO
    // =========================================================================
    $options = [
        // ERRMODE_EXCEPTION: Lança exceções em caso de erro, permitindo
        // tratamento adequado com try/catch
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        
        // FETCH_ASSOC: Retorna resultados como arrays associativos
        // Ex: $row['nome'] em vez de $row[0]
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        
        // EMULATE_PREPARES = false: Usa prepared statements nativos do MySQL
        // Isso é mais seguro contra SQL Injection
        PDO::ATTR_EMULATE_PREPARES   => false,
        
        // TIMEOUT: Tempo máximo de espera pela conexão (em segundos)
        // Evita que o site trave se o banco estiver lento ou offline
        PDO::ATTR_TIMEOUT            => 5,
    ];

    // =========================================================================
    // TENTATIVA DE CONEXÃO
    // =========================================================================
    try {
        // Cria nova instância PDO com as configurações definidas
        $pdo = new PDO($dsn, $user, $pass, $options);
        
        // Conexão bem-sucedida, retorna o objeto PDO
        return $pdo;
        
    } catch (\PDOException $e) {
        // =====================================================================
        // TRATAMENTO DE ERRO
        // =====================================================================
        // Em caso de falha na conexão (banco offline, credenciais erradas, etc.)
        
        // Em PRODUÇÃO: Logar o erro em arquivo para análise posterior
        // NUNCA exiba mensagens de erro do banco para o usuário final!
        // error_log("[ERRO PDO] " . date('Y-m-d H:i:s') . " - " . $e->getMessage());
        
        // Retorna null para sinalizar que o banco não está disponível
        // A aplicação deve ter um fallback para funcionar sem o banco
        return null;
    }
}

/**
 * ============================================================================
 * FUNÇÕES AUXILIARES (Opcionais - Para uso futuro)
 * ============================================================================
 */

/**
 * Verifica se a conexão com o banco está ativa.
 * 
 * @return bool True se conectado, False caso contrário
 */
function isDatabaseConnected(): bool {
    return getPDO() !== null;
}

/**
 * Executa uma query SELECT de forma segura usando prepared statements.
 * 
 * @param string $sql Query SQL com placeholders (ex: "SELECT * FROM tabela WHERE id = ?")
 * @param array $params Parâmetros para substituir os placeholders
 * @return array|false Retorna array de resultados ou false em caso de erro
 * 
 * EXEMPLO DE USO:
 * $reunioes = executeQuery(
 *     "SELECT * FROM grupoquarentena_reunioes WHERE dia_semana = ?",
 *     [1]  // 1 = Segunda-feira
 * );
 */
function executeQuery(string $sql, array $params = []): array|false {
    $pdo = getPDO();
    
    if ($pdo === null) {
        return false;
    }
    
    try {
        // Prepara a query (evita SQL Injection)
        $stmt = $pdo->prepare($sql);
        
        // Executa com os parâmetros fornecidos
        $stmt->execute($params);
        
        // Retorna todos os resultados
        return $stmt->fetchAll();
        
    } catch (\PDOException $e) {
        // Loga o erro (em produção)
        // error_log("[QUERY ERROR] " . $e->getMessage());
        return false;
    }
}
?>
