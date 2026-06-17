<?php
// =====================================================================
// rotas/contato.php
// ---------------------------------------------------------------------
// "Fale Conosco": o visitante envia uma mensagem; o admin lê e marca
// como lida.
//
// Rota pública:
//   POST /api/contato   { nome, email, telefone, mensagem }
// Rotas do admin (exigem login):
//   GET   /api/admin/mensagens?lida=true|false&page=0&size=20
//   PATCH /api/admin/mensagens/{id}/lida   -> marca como lida
// =====================================================================

defined('IDTNPR') or exit('Acesso negado.');

/** Transforma a linha do banco no JSON de uma mensagem. */
function mensagem_para_array($m)
{
    return array(
        'id'       => (int) $m['id'],
        'nome'     => $m['nome'],
        'email'    => $m['email'],
        'telefone' => $m['telefone'],
        'mensagem' => $m['mensagem'],
        'lida'     => (int) $m['lida'] === 1,
        'criadoEm' => data_iso($m['criado_em']),
    );
}

/** POST /api/contato */
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

    consultar(
        'INSERT INTO mensagem_contato (nome, email, telefone, mensagem, lida, criado_em)
         VALUES (?, ?, ?, ?, 0, NOW())',
        array($nome, $email, $telefone, $mensagem)
    );

    $id    = (int) banco()->lastInsertId();
    $linha = consultar('SELECT * FROM mensagem_contato WHERE id = ?', array($id))->fetch();
    responder(201, mensagem_para_array($linha));
}

/** GET /api/admin/mensagens?lida=...&page=0&size=20 */
function admin_listar_mensagens($params)
{
    exigir_admin();

    $page = max(0, (int) (isset($_GET['page']) ? $_GET['page'] : 0));
    $size = (int) (isset($_GET['size']) ? $_GET['size'] : 20);
    if ($size < 1)   { $size = 20; }
    if ($size > 100) { $size = 100; }

    // Filtro opcional por lida (true/false).
    $where = '';
    $args  = array();
    if (isset($_GET['lida']) && $_GET['lida'] !== '') {
        $lida  = ($_GET['lida'] === 'true' || $_GET['lida'] === '1') ? 1 : 0;
        $where = ' WHERE lida = ?';
        $args[] = $lida;
    }

    $total  = (int) consultar('SELECT COUNT(*) FROM mensagem_contato' . $where, $args)->fetchColumn();
    $offset = $page * $size;

    $linhas = consultar(
        'SELECT * FROM mensagem_contato' . $where . ' ORDER BY criado_em DESC LIMIT ' . $size . ' OFFSET ' . $offset,
        $args
    )->fetchAll();

    $conteudo = array_map('mensagem_para_array', $linhas);
    responder(200, pagina($conteudo, $page, $size, $total));
}

/** PATCH /api/admin/mensagens/{id}/lida */
function admin_marcar_mensagem_lida($params)
{
    exigir_admin();
    $id = (int) $params['id'];

    $existe = consultar('SELECT 1 FROM mensagem_contato WHERE id = ?', array($id))->fetch();
    if (!$existe) {
        responder_erro(404, 'Mensagem nao encontrada: ' . $id);
    }

    consultar('UPDATE mensagem_contato SET lida = 1 WHERE id = ?', array($id));

    $linha = consultar('SELECT * FROM mensagem_contato WHERE id = ?', array($id))->fetch();
    responder(200, mensagem_para_array($linha));
}
