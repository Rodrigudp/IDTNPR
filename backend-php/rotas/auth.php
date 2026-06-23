<?php

defined('IDTNPR') or exit('Acesso negado.');

function login($params)
{
    $dados = ler_json();
    $email = v_texto($dados, 'email');
    $senha = isset($dados['senha']) ? (string) $dados['senha'] : '';

    $erros = array();
    v_obrigatorio($erros, $email, 'email');
    v_email($erros, $email, 'email');
    v_obrigatorio($erros, $senha, 'senha');
    v_finalizar($erros);

    $ip = ip_cliente();
    if (login_bloqueado($ip)) {
        responder_erro(429, 'Muitas tentativas de login. Tente novamente em alguns minutos.');
    }

    $usuario = consultar(
        'SELECT usuemail, ususenha FROM usuarios WHERE usuemail = ? AND usuexclusao IS NULL',
        array($email)
    )->fetch();

    $senhaConfere = $usuario && password_verify($senha, $usuario['ususenha']);

    if (!$usuario || !$senhaConfere) {
        login_registrar_falha($ip);
        responder_erro(401, 'Credenciais invalidas.');
    }

    login_registrar_sucesso($ip);

    $token   = jwt_gerar($usuario['usuemail'], 'ROLE_ADMIN');
    $minutos = (int) env('JWT_EXP_MINUTES', 480);

    responder(200, array(
        'accessToken' => $token,
        'tokenType'   => 'Bearer',
        'expiresIn'   => $minutos * 60,
    ));
}
