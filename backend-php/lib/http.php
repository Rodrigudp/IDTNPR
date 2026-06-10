<?php
// =====================================================================
// lib/http.php
// ---------------------------------------------------------------------
// Funções para LER o que o cliente enviou e para RESPONDER em JSON.
// Toda resposta da API passa por aqui, ficando num formato padronizado.
// =====================================================================

defined('IDTNPR') or exit('Acesso negado.');

/**
 * Lê o corpo da requisição (que vem em JSON) e devolve como array.
 * Usado nas rotas que recebem dados (login, abrir protocolo, etc.).
 */
function ler_json()
{
    $corpo = file_get_contents('php://input');
    if ($corpo === '' || $corpo === false) {
        return array();
    }

    $dados = json_decode($corpo, true);
    if (!is_array($dados)) {
        responder_erro(400, 'Corpo da requisicao nao e um JSON valido.');
    }
    return $dados;
}

/**
 * Responde ao cliente com um JSON e um código HTTP, e encerra a execução.
 * Exemplo:  responder(200, array('ok' => true));
 */
function responder($status, $dados)
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * Responde sem corpo (usado, por exemplo, ao remover um projeto -> 204).
 */
function responder_vazio($status)
{
    http_response_code($status);
    exit;
}

/**
 * Responde um ERRO padronizado (mesmo formato que o back-end antigo em Java).
 * $campos é uma lista opcional de erros por campo (na validação de formulários).
 */
function responder_erro($status, $mensagem, $campos = array())
{
    responder($status, array(
        'timestamp' => date('c'),
        'status'    => $status,
        'erro'      => texto_status($status),
        'mensagem'  => $mensagem,
        'campos'    => $campos,
    ));
}

/**
 * Texto curto para cada código HTTP (apenas para deixar a resposta legível).
 */
function texto_status($status)
{
    $mapa = array(
        200 => 'OK',
        201 => 'Created',
        204 => 'No Content',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        429 => 'Too Many Requests',
        500 => 'Internal Server Error',
    );
    return isset($mapa[$status]) ? $mapa[$status] : 'Error';
}

/**
 * Converte uma data do banco ("2026-06-10 14:30:00") para o formato
 * usado em APIs ("2026-06-10T14:30:00"). Mantém igual ao back-end antigo.
 */
function data_iso($valor)
{
    if ($valor === null || $valor === '') {
        return null;
    }
    return str_replace(' ', 'T', $valor);
}

/**
 * Monta uma resposta "paginada" (uma página de uma lista grande).
 *  - content        : os itens desta página
 *  - page / size    : número da página (começa em 0) e quantos itens por página
 *  - totalElements  : total de itens no banco
 *  - totalPages     : total de páginas
 */
function pagina($conteudo, $page, $size, $total)
{
    return array(
        'content'       => $conteudo,
        'page'          => $page,
        'size'          => $size,
        'totalElements' => $total,
        'totalPages'    => $size > 0 ? (int) ceil($total / $size) : 0,
    );
}
