<?php
// =====================================================================
// lib/jwt.php
// ---------------------------------------------------------------------
// Cria e valida o "token" de login do admin.
//
// O token é um JWT (JSON Web Token): um texto assinado que prova que a
// pessoa fez login. Ele é enviado em cada requisição administrativa no
// cabeçalho "Authorization: Bearer <token>" (igual ao back-end antigo).
//
// O back-end antigo (Java) assinava com chaves RSA (mais complexo).
// Aqui usamos HMAC-SHA256, que é muito mais simples: basta uma senha
// secreta (JWT_SECRET, definida no .env). O resultado para o cliente é
// exatamente o mesmo formato de token.
// =====================================================================

defined('IDTNPR') or exit('Acesso negado.');

/** Codifica em Base64 "seguro para URL" (sem os caracteres + / =). */
function base64url_encode($texto)
{
    return rtrim(strtr(base64_encode($texto), '+/', '-_'), '=');
}

/** Decodifica o Base64 "seguro para URL". */
function base64url_decode($texto)
{
    return base64_decode(strtr($texto, '-_', '+/'));
}

/**
 * Gera um token para o usuário (identificado pelo e-mail) com seu papel
 * (ex.: "ROLE_ADMIN"). O token vale por JWT_EXP_MINUTES minutos.
 */
function jwt_gerar($email, $papel)
{
    $segredo = env('JWT_SECRET');
    $minutos = (int) env('JWT_EXP_MINUTES', 480);
    $agora   = time();

    // O JWT tem 3 partes: cabeçalho, dados e assinatura.
    $cabecalho = array('alg' => 'HS256', 'typ' => 'JWT');
    $dados = array(
        'sub'   => $email,                  // "subject": quem é o usuário
        'roles' => $papel,                  // permissão (ex.: ROLE_ADMIN)
        'iat'   => $agora,                  // emitido em (timestamp)
        'exp'   => $agora + $minutos * 60,  // expira em (timestamp)
    );

    $parte1 = base64url_encode(json_encode($cabecalho));
    $parte2 = base64url_encode(json_encode($dados));
    // A assinatura prova que o token não foi adulterado.
    $assinatura = base64url_encode(hash_hmac('sha256', "$parte1.$parte2", $segredo, true));

    return "$parte1.$parte2.$assinatura";
}

/**
 * Confere se um token é válido e não expirou.
 * Devolve os dados de dentro do token, ou null se for inválido.
 */
function jwt_validar($token)
{
    $segredo = env('JWT_SECRET');
    $partes  = explode('.', $token);
    if (count($partes) !== 3) {
        return null; // formato errado
    }

    list($parte1, $parte2, $assinatura) = $partes;

    // Recalcula a assinatura e compara com a recebida.
    $esperada = base64url_encode(hash_hmac('sha256', "$parte1.$parte2", $segredo, true));
    if (!hash_equals($esperada, $assinatura)) {
        return null; // assinatura não confere -> token adulterado
    }

    $dados = json_decode(base64url_decode($parte2), true);
    if (!is_array($dados) || !isset($dados['exp']) || $dados['exp'] < time()) {
        return null; // sem expiração ou já expirou
    }

    return $dados;
}
