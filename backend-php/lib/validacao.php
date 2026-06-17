<?php
// =====================================================================
// lib/validacao.php
// ---------------------------------------------------------------------
// Funções simples para validar os dados que o cliente envia.
// A ideia: cada rota junta os erros numa lista e, no final, chama
// v_finalizar($erros). Se houver algum erro, a API responde 400 com
// a lista de campos com problema (igual ao back-end antigo).
// =====================================================================

defined('IDTNPR') or exit('Acesso negado.');

/**
 * Lê um campo de texto do array de dados, já com trim (sem espaços nas pontas).
 * Se o campo não veio, devolve string vazia.
 */
function v_texto($dados, $chave)
{
    if (isset($dados[$chave]) && is_string($dados[$chave])) {
        return trim($dados[$chave]);
    }
    return '';
}

/** Acrescenta um erro à lista. */
function v_erro(&$erros, $campo, $mensagem)
{
    $erros[] = array('campo' => $campo, 'mensagem' => $mensagem);
}

/** Campo é obrigatório (não pode ser vazio). */
function v_obrigatorio(&$erros, $valor, $campo)
{
    if ($valor === '') {
        v_erro($erros, $campo, 'Campo obrigatorio.');
    }
}

/**
 * Conta os caracteres de um texto. Usa mb_strlen (que conta acentos
 * corretamente) quando disponível; senão, cai para strlen.
 */
function tamanho_texto($texto)
{
    return function_exists('mb_strlen') ? mb_strlen($texto) : strlen($texto);
}

/** Não pode passar de $max caracteres. */
function v_max(&$erros, $valor, $campo, $max)
{
    if (tamanho_texto($valor) > $max) {
        v_erro($erros, $campo, "Maximo de $max caracteres.");
    }
}

/** Precisa ter pelo menos $min caracteres (quando preenchido). */
function v_min(&$erros, $valor, $campo, $min)
{
    if ($valor !== '' && tamanho_texto($valor) < $min) {
        v_erro($erros, $campo, "Minimo de $min caracteres.");
    }
}

/** Precisa ser um e-mail válido (quando preenchido). */
function v_email(&$erros, $valor, $campo)
{
    if ($valor !== '' && !filter_var($valor, FILTER_VALIDATE_EMAIL)) {
        v_erro($erros, $campo, 'E-mail invalido.');
    }
}

/** O valor precisa estar dentro de uma lista de opções permitidas. */
function v_um_de(&$erros, $valor, $campo, $opcoes)
{
    if (!in_array($valor, $opcoes, true)) {
        v_erro($erros, $campo, 'Valor invalido. Opcoes: ' . implode(', ', $opcoes) . '.');
    }
}

/**
 * Se houver algum erro, responde 400 e encerra. Caso contrário, segue em frente.
 */
function v_finalizar($erros)
{
    if (!empty($erros)) {
        responder_erro(400, 'Ha campos invalidos na requisicao.', $erros);
    }
}
