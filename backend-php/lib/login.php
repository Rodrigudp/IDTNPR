<?php

defined('IDTNPR') or exit('Acesso negado.');

const LOGIN_MAX_TENTATIVAS = 5;
const LOGIN_JANELA_MINUTOS = 15;

function ip_cliente()
{
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
}

function login_bloqueado($ip)
{
    $sql = "SELECT tentativas FROM tentativas_login
            WHERE tenip = ? AND tenexclusao IS NULL
              AND teniniciojanela > (NOW() - INTERVAL '" . LOGIN_JANELA_MINUTOS . " minutes')";
    $linha = consultar($sql, array($ip))->fetch();

    return $linha && (int) $linha['tentativas'] >= LOGIN_MAX_TENTATIVAS;
}

function login_registrar_falha($ip)
{
    $linha = consultar(
        'SELECT tenid FROM tentativas_login WHERE tenip = ? AND tenexclusao IS NULL ORDER BY tenid DESC LIMIT 1',
        array($ip)
    )->fetch();

    if (!$linha) {
        consultar(
            'INSERT INTO tentativas_login (tenip, tentativas, teniniciojanela, teninclusao) VALUES (?, 1, NOW(), NOW())',
            array($ip)
        );
        return;
    }

    consultar(
        "UPDATE tentativas_login
         SET tentativas = CASE
                WHEN teniniciojanela > (NOW() - INTERVAL '" . LOGIN_JANELA_MINUTOS . " minutes') THEN tentativas + 1
                ELSE 1
             END,
             teniniciojanela = CASE
                WHEN teniniciojanela > (NOW() - INTERVAL '" . LOGIN_JANELA_MINUTOS . " minutes') THEN teniniciojanela
                ELSE NOW()
             END
         WHERE tenid = ?",
        array($linha['tenid'])
    );
}

function login_registrar_sucesso($ip)
{
    consultar('DELETE FROM tentativas_login WHERE tenip = ?', array($ip));
}
