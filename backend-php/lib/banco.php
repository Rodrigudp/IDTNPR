<?php
// =====================================================================
// lib/banco.php
// ---------------------------------------------------------------------
// Conexão com o banco de dados MySQL (o banco usado na Locaweb).
// Usamos PDO, que é a forma recomendada e segura de acessar o banco
// no PHP (com "prepared statements", que evitam SQL injection).
// =====================================================================

defined('IDTNPR') or exit('Acesso negado.');

/**
 * Devolve a conexão com o banco. Na primeira chamada conecta; nas
 * próximas, reaproveita a mesma conexão (graças ao "static").
 */
function banco()
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $host  = env('DB_HOST', 'portalidtnpr.mysql.dbaas.com.br');
    $porta = env('DB_PORT', '3306'); 
    $nome  = env('DB_NAME', 'portalidtnpr');
    $user  = env('DB_USER', 'portalidtnpr');
    $senha = env('DB_PASSWORD', '');

    $dsn = "mysql:host=$host;port=$porta;dbname=$nome;charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $senha, array(
            // Erros do banco viram exceções (mais fácil de detectar problemas).
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            // Resultados vêm como arrays com nomes de coluna ("id", "nome"...).
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Usa prepared statements de verdade (mais seguro).
            PDO::ATTR_EMULATE_PREPARES   => false,
        ));
    } catch (PDOException $e) {
        responder_erro(500, 'Nao foi possivel conectar ao banco de dados.');
    }

    return $pdo;
}

/**
 * Atalho para executar uma consulta SQL com parâmetros.
 * Exemplo:  consultar('SELECT * FROM projeto WHERE id = ?', array($id))
 * Devolve o "statement", de onde você lê os resultados com ->fetch() / ->fetchAll().
 */
function consultar($sql, $parametros = array())
{
    $stmt = banco()->prepare($sql);
    $stmt->execute($parametros);
    return $stmt;
}
