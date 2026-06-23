<?php

defined('IDTNPR') or exit('Acesso negado.');

function mensagem_para_array($m)
{
    return array(
        'id'       => (int) $m['menid'],
        'nome'     => $m['mennome'],
        'email'    => $m['menemail'],
        'telefone' => $m['mentelefone'],
        'mensagem' => $m['menmensagem'],
        'lida'     => (bool) $m['menlida'],
        'criadoEm' => data_iso($m['meninclusao']),
    );
}

function enviar_contato($params)
{
    $dados = ler_json();

    $nome     = v_texto($dados, 'nome');
    $email    = v_texto($dados, 'email');
    $telefone = v_texto($dados, 'telefone');
    $mensagem = v_texto($dados, 'mensagem');

    $erros = array();
    v_obrigatorio($erros, $nome, 'nome');
    v_max($erros, $nome, 'nome', 150);
    v_obrigatorio($erros, $email, 'email');
    v_email($erros, $email, 'email');
    v_max($erros, $telefone, 'telefone', 20);
    v_obrigatorio($erros, $mensagem, 'mensagem');
    v_max($erros, $mensagem, 'mensagem', 5000);
    v_finalizar($erros);

    $linha = consultar(
        'INSERT INTO mensagens_contato (mennome, menemail, mentelefone, menmensagem, menlida, meninclusao)
         VALUES (?, ?, ?, ?, FALSE, NOW())
         RETURNING *',
        array($nome, $email, $telefone, $mensagem)
    )->fetch();

    responder(201, mensagem_para_array($linha));
}

function admin_listar_mensagens($params)
{
    exigir_admin();

    $page = max(0, (int) (isset($_GET['page']) ? $_GET['page'] : 0));
    $size = (int) (isset($_GET['size']) ? $_GET['size'] : 20);
    if ($size < 1)   { $size = 20; }
    if ($size > 100) { $size = 100; }

    $where = ' WHERE menexclusao IS NULL';
    $args  = array();
    if (isset($_GET['lida']) && $_GET['lida'] !== '') {
        $lida  = ($_GET['lida'] === 'true' || $_GET['lida'] === '1');
        $where .= ' AND menlida = ?';
        $args[] = $lida;
    }

    $total  = (int) consultar('SELECT COUNT(*) FROM mensagens_contato' . $where, $args)->fetchColumn();
    $offset = $page * $size;

    $linhas = consultar(
        'SELECT * FROM mensagens_contato' . $where . ' ORDER BY meninclusao DESC LIMIT ' . $size . ' OFFSET ' . $offset,
        $args
    )->fetchAll();

    responder(200, pagina(array_map('mensagem_para_array', $linhas), $page, $size, $total));
}

function admin_marcar_mensagem_lida($params)
{
    exigir_admin();
    $id = (int) $params['id'];

    $linha = consultar(
        'UPDATE mensagens_contato SET menlida = TRUE WHERE menid = ? AND menexclusao IS NULL RETURNING *',
        array($id)
    )->fetch();

    if (!$linha) {
        responder_erro(404, 'Mensagem nao encontrada: ' . $id);
    }

    responder(200, mensagem_para_array($linha));
}
