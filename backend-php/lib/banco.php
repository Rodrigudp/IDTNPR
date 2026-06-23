<?php

defined('IDTNPR') or exit('Acesso negado.');

function banco()
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $host  = env('DB_HOST', 'localhost');
    $porta = env('DB_PORT', '5432');
    $nome  = env('DB_NAME', 'testeextensao');
    $user  = env('DB_USER', 'postgres');
    $senha = env('DB_PASSWORD', '');

    $dsn = "pgsql:host=$host;port=$porta;dbname=$nome";

    try {
        $pdo = new PDO($dsn, $user, $senha, array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ));
    } catch (PDOException $e) {
        responder_erro(500, 'Nao foi possivel conectar ao banco de dados.');
    }

    return $pdo;
}

function consultar($sql, $parametros = array())
{
    $stmt = banco()->prepare($sql);
    $stmt->execute($parametros);
    return $stmt;
}
