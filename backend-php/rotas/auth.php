<?php
// =====================================================================
// rotas/auth.php
// ---------------------------------------------------------------------
// Login do painel administrativo.
//   POST /api/auth/login   { "email": "...", "senha": "..." }
// Em caso de sucesso, devolve um token para usar nas rotas /api/admin/...
// =====================================================================

defined('IDTNPR') or exit('Acesso negado.');

function login($params)
{
    $dados = ler_json();
    $email = v_texto($dados, 'email');
    $senha = isset($dados['senha']) ? (string) $dados['senha'] : '';

    // 1) Valida os campos enviados.
    $erros = array();
    v_obrigatorio($erros, $email, 'email');
    v_email($erros, $email, 'email');
    v_obrigatorio($erros, $senha, 'senha');
    v_finalizar($erros);

    // 2) Protege contra força bruta (muitas tentativas do mesmo IP).
    $ip = ip_cliente();
    if (login_bloqueado($ip)) {
        responder_erro(429, 'Muitas tentativas de login. Tente novamente em alguns minutos.');
    }

    // 3) Procura o usuário pelo e-mail e confere a senha.
    $usuario = consultar(
        'SELECT email, senha, role, enabled FROM usuario WHERE email = ?',
        array($email)
    )->fetch();

    $senhaConfere = $usuario && password_verify($senha, $usuario['senha']);
    $ativo        = $usuario && (int) $usuario['enabled'] === 1;

    if (!$usuario || !$ativo || !$senhaConfere) {
        // Mensagem genérica de propósito: não revela se o e-mail existe.
        login_registrar_falha($ip);
        responder_erro(401, 'Credenciais invalidas.');
    }

    // 4) Deu certo: zera as tentativas e gera o token.
    login_registrar_sucesso($ip);

    $token   = jwt_gerar($usuario['email'], 'ROLE_' . $usuario['role']);
    $minutos = (int) env('JWT_EXP_MINUTES', 480);

    responder(200, array(
        'accessToken' => $token,
        'tokenType'   => 'Bearer',
        'expiresIn'   => $minutos * 60, // validade em segundos
    ));
}
