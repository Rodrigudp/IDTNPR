<?php
// =====================================================================
// lib/auth.php
// ---------------------------------------------------------------------
// Protege as rotas administrativas (/api/admin/...): exige que o cliente
// envie um token válido de ADMIN. Basta chamar exigir_admin() no início
// da rota — se o token faltar ou for inválido, a requisição é barrada.
// =====================================================================

defined('IDTNPR') or exit('Acesso negado.');

/**
 * Lê o token do cabeçalho "Authorization: Bearer <token>".
 * Devolve só o token (sem a palavra "Bearer"), ou string vazia.
 */
function token_da_requisicao()
{
    $valor = '';

    if (function_exists('getallheaders')) {
        $cabecalhos = getallheaders();
        if (isset($cabecalhos['Authorization'])) {
            $valor = $cabecalhos['Authorization'];
        } elseif (isset($cabecalhos['authorization'])) {
            $valor = $cabecalhos['authorization'];
        }
    }

    // Alguns servidores entregam o cabeçalho por aqui (ver o .htaccess).
    if ($valor === '' && isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $valor = $_SERVER['HTTP_AUTHORIZATION'];
    }

    if (stripos($valor, 'Bearer ') === 0) {
        return trim(substr($valor, 7));
    }
    return '';
}

/**
 * Exige um admin logado. Use no início de qualquer rota /api/admin/...
 * - Sem token / token inválido  -> responde 401 (nao autorizado)
 * - Token válido, mas sem ADMIN  -> responde 403 (acesso negado)
 */
function exigir_admin()
{
    $token = token_da_requisicao();
    $dados = $token !== '' ? jwt_validar($token) : null;

    if ($dados === null) {
        responder_erro(401, 'Credenciais invalidas.');
    }

    $papeis = isset($dados['roles']) ? (string) $dados['roles'] : '';
    if (strpos($papeis, 'ROLE_ADMIN') === false) {
        responder_erro(403, 'Acesso negado.');
    }

    return $dados;
}
