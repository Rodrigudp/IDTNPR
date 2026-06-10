<?php
// =====================================================================
// lib/login.php
// ---------------------------------------------------------------------
// Proteção contra "força bruta" no login: se um mesmo IP errar a senha
// muitas vezes seguidas, ele fica bloqueado por alguns minutos.
//
// Regra (igual ao back-end antigo): 5 tentativas erradas em 15 minutos.
// Guardamos as tentativas na tabela "login_tentativa" do banco.
//
// IMPORTANTE: toda a conta de tempo é feita pelo próprio MySQL (NOW() e
// INTERVAL). Assim não misturamos o relógio do PHP com o do banco — o
// que evitaria erros caso os dois estivessem em fusos horários diferentes.
// =====================================================================

defined('IDTNPR') or exit('Acesso negado.');

// Constantes da regra.
const LOGIN_MAX_TENTATIVAS = 5;
const LOGIN_JANELA_MINUTOS = 15;

/** Descobre o IP de quem está acessando. */
function ip_cliente()
{
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
}

/**
 * Diz se o IP está bloqueado: tem 5+ tentativas E ainda está dentro da
 * janela de 15 minutos. (Se a janela já passou, a linha é ignorada.)
 */
function login_bloqueado($ip)
{
    $sql = 'SELECT tentativas FROM login_tentativa
            WHERE ip = ? AND inicio_janela > (NOW() - INTERVAL ' . LOGIN_JANELA_MINUTOS . ' MINUTE)';
    $linha = consultar($sql, array($ip))->fetch();

    return $linha && (int) $linha['tentativas'] >= LOGIN_MAX_TENTATIVAS;
}

/**
 * Registra uma tentativa que FALHOU (senha errada).
 * - Se ainda está dentro da janela, soma +1 nas tentativas.
 * - Se a janela já passou (ou é a primeira falha), recomeça a contagem em 1.
 */
function login_registrar_falha($ip)
{
    $janela = '(NOW() - INTERVAL ' . LOGIN_JANELA_MINUTOS . ' MINUTE)';
    $sql = 'INSERT INTO login_tentativa (ip, tentativas, inicio_janela)
            VALUES (?, 1, NOW())
            ON DUPLICATE KEY UPDATE
                tentativas    = IF(inicio_janela > ' . $janela . ', tentativas + 1, 1),
                inicio_janela = IF(inicio_janela > ' . $janela . ', inicio_janela, NOW())';
    consultar($sql, array($ip));
}

/** Registra um login que DEU CERTO (zera o contador daquele IP). */
function login_registrar_sucesso($ip)
{
    consultar('DELETE FROM login_tentativa WHERE ip = ?', array($ip));
}
