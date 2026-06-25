<?php
// =====================================================================
// rotas/protocolos.php
// ---------------------------------------------------------------------
// Protocolo Digital: o cidadão abre uma solicitação, acompanha pelo
// número e pode anexar arquivos. O admin lista e muda o status.
//
// Rotas públicas:
//   POST /api/protocolos                  -> abrir solicitação
//   GET  /api/protocolos/{numero}         -> acompanhar (CPF mascarado)
//   POST /api/protocolos/{numero}/anexos  -> anexar arquivo
// Rotas do admin (exigem login):
//   GET   /api/admin/protocolos                  -> listar
//   GET   /api/admin/protocolos/{numero}         -> detalhar (CPF completo)
//   PATCH /api/admin/protocolos/{numero}/status  -> mudar status
// =====================================================================

defined('IDTNPR') or exit('Acesso negado.');

// Tipos de solicitação aceitos (igual ao back-end antigo).
const TIPOS_SOLICITACAO = array(
    'INFORMACAO', 'SOLICITACAO_SERVICO', 'RECLAMACAO', 'DENUNCIA', 'ELOGIO', 'OUTRO',
);

// Estados possíveis de um protocolo.
const STATUS_PROTOCOLO = array('ABERTO', 'EM_ANALISE', 'CONCLUIDO', 'ARQUIVADO');

const TIPOS_ANEXO = array('application/pdf', 'image/jpeg', 'image/png');

/**
 * Para cada status, diz para quais outros ele pode mudar.
 * (Regra de negócio: revise conforme o fluxo real do IDTNPR.)
 */
function transicoes_permitidas($status)
{
    switch ($status) {
        case 'ABERTO':     return array('EM_ANALISE', 'ARQUIVADO');
        case 'EM_ANALISE': return array('CONCLUIDO', 'ARQUIVADO');
        case 'CONCLUIDO':  return array('ARQUIVADO');
        default:           return array(); // ARQUIVADO é estado final
    }
}

function gerar_numero_protocolo()
{
    do {
        $numero = date('Y') . '-' . strtoupper(bin2hex(random_bytes(12)));
        $existe = consultar('SELECT 1 FROM protocolo WHERE numero = ?', array($numero))->fetch();
    } while ($existe);
    return $numero;
}

/** Mantém só os 2 últimos dígitos do CPF: "***.***.***-12". */
function mascarar_cpf($cpf)
{
    if ($cpf === null || strlen($cpf) < 2) {
        return '***.***.***-**';
    }
    return '***.***.***-' . substr($cpf, -2);
}

/** Busca um protocolo pelo número (ou responde 404). */
function buscar_protocolo($numero)
{
    $p = consultar('SELECT * FROM protocolo WHERE numero = ?', array($numero))->fetch();
    if (!$p) {
        responder_erro(404, 'Protocolo nao encontrado: ' . $numero);
    }
    return $p;
}

function anexos_do_protocolo($protocoloId)
{
    return consultar('SELECT * FROM anexo WHERE protocolo_id = ? ORDER BY id', array($protocoloId))->fetchAll();
}

/** Monta o JSON de um anexo. */
function anexo_para_array($a)
{
    return array(
        'id'           => (int) $a['id'],
        'nomeOriginal' => $a['nome_original'],
        'contentType'  => $a['content_type'],
        'tamanhoBytes' => $a['tamanho_bytes'] !== null ? (int) $a['tamanho_bytes'] : null,
    );
}

/**
 * Monta o JSON de um protocolo. Se $mascarar = true, o CPF sai mascarado
 * (usado nas respostas públicas, por causa da LGPD).
 */
function protocolo_para_array($p, $mascarar)
{
    $anexos = anexos_do_protocolo($p['id']);

    return array(
        'numero'          => $p['numero'],
        'nome'            => $p['nome'],
        'cpf'             => $mascarar ? mascarar_cpf($p['cpf']) : $p['cpf'],
        'email'           => $p['email'],
        'telefone'        => $p['telefone'],
        'tipoSolicitacao' => $p['tipo_solicitacao'],
        'descricao'       => $p['descricao'],
        'status'          => $p['status'],
        'criadoEm'        => data_iso($p['criado_em']),
        'atualizadoEm'    => data_iso($p['atualizado_em']),
        'anexos'          => array_map('anexo_para_array', $anexos),
    );
}

// ---------------------------------------------------------------------
// Rotas públicas
// ---------------------------------------------------------------------

/** POST /api/protocolos */
function abrir_protocolo($params)
{
    $dados = ler_json();

    $nome      = v_texto($dados, 'nome');
    $cpf       = v_texto($dados, 'cpf');
    $email     = v_texto($dados, 'email');
    $telefone  = v_texto($dados, 'telefone');
    $tipo      = v_texto($dados, 'tipoSolicitacao');
    $descricao = v_texto($dados, 'descricao');

    $erros = array();
    v_obrigatorio($erros, $nome, 'nome');
    v_max($erros, $nome, 'nome', 150);
    v_obrigatorio($erros, $cpf, 'cpf');
    v_min($erros, $cpf, 'cpf', 11);
    v_max($erros, $cpf, 'cpf', 14);
    v_obrigatorio($erros, $email, 'email');
    v_email($erros, $email, 'email');
    v_max($erros, $telefone, 'telefone', 20);
    v_obrigatorio($erros, $tipo, 'tipoSolicitacao');
    v_um_de($erros, $tipo, 'tipoSolicitacao', TIPOS_SOLICITACAO);
    v_obrigatorio($erros, $descricao, 'descricao');
    v_max($erros, $descricao, 'descricao', 5000);
    v_finalizar($erros);

    $numero = gerar_numero_protocolo();

    consultar(
        'INSERT INTO protocolo (numero, nome, cpf, email, telefone, tipo_solicitacao, descricao, status, criado_em, atualizado_em)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())',
        array($numero, $nome, $cpf, $email, $telefone, $tipo, $descricao, 'ABERTO')
    );

    $p = buscar_protocolo($numero);
    // 201 Created, com a resposta pública (CPF mascarado).
    responder(201, protocolo_para_array($p, true));
}

/** GET /api/protocolos/{numero} */
function acompanhar_protocolo($params)
{
    $p = buscar_protocolo($params['numero']);
    responder(200, protocolo_para_array($p, true));
}

/** POST /api/protocolos/{numero}/anexos  (campo do formulário: "arquivo") */
function anexar_protocolo($params)
{
    $p = buscar_protocolo($params['numero']);

    $dados = salvar_upload('arquivo', 'protocolos', TIPOS_ANEXO, 'PDF, JPG, PNG');

    consultar(
        'INSERT INTO anexo (protocolo_id, nome_original, content_type, tamanho_bytes, caminho, criado_em)
         VALUES (?, ?, ?, ?, ?, NOW())',
        array($p['id'], $dados['nome_original'], $dados['content_type'], $dados['tamanho_bytes'], $dados['caminho'])
    );

    consultar('UPDATE protocolo SET atualizado_em = NOW() WHERE id = ?', array($p['id']));

    $p = buscar_protocolo($params['numero']);
    responder(200, protocolo_para_array($p, true));
}

// ---------------------------------------------------------------------
// Rotas do admin (exigem login)
// ---------------------------------------------------------------------

/** GET /api/admin/protocolos?status=...&page=0&size=20 */
function admin_listar_protocolos($params)
{
    exigir_admin();

    $status = isset($_GET['status']) ? trim($_GET['status']) : '';
    $page   = max(0, (int) (isset($_GET['page']) ? $_GET['page'] : 0));
    $size   = (int) (isset($_GET['size']) ? $_GET['size'] : 20);
    if ($size < 1)   { $size = 20; }
    if ($size > 100) { $size = 100; }

    $where = '';
    $args  = array();
    if ($status !== '') {
        if (!in_array($status, STATUS_PROTOCOLO, true)) {
            responder_erro(400, 'Status invalido. Opcoes: ' . implode(', ', STATUS_PROTOCOLO) . '.');
        }
        $where = ' WHERE status = ?';
        $args[] = $status;
    }

    $total = (int) consultar('SELECT COUNT(*) FROM protocolo' . $where, $args)->fetchColumn();

    $offset = $page * $size;
    $linhas = consultar(
        'SELECT * FROM protocolo' . $where . ' ORDER BY criado_em DESC LIMIT ' . $size . ' OFFSET ' . $offset,
        $args
    )->fetchAll();

    $conteudo = array();
    foreach ($linhas as $p) {
        $conteudo[] = protocolo_para_array($p, false); // admin vê o CPF completo
    }

    responder(200, pagina($conteudo, $page, $size, $total));
}

/** GET /api/admin/protocolos/{numero} */
function admin_detalhar_protocolo($params)
{
    exigir_admin();
    $p = buscar_protocolo($params['numero']);
    responder(200, protocolo_para_array($p, false));
}

/** PATCH /api/admin/protocolos/{numero}/status   { "status": "EM_ANALISE" } */
function admin_atualizar_status($params)
{
    exigir_admin();

    $dados      = ler_json();
    $novoStatus = v_texto($dados, 'status');

    $erros = array();
    v_obrigatorio($erros, $novoStatus, 'status');
    v_um_de($erros, $novoStatus, 'status', STATUS_PROTOCOLO);
    v_finalizar($erros);

    $p     = buscar_protocolo($params['numero']);
    $atual = $p['status'];

    // Só permite mudanças de status válidas.
    if ($atual !== $novoStatus && !in_array($novoStatus, transicoes_permitidas($atual), true)) {
        responder_erro(400, "Transicao de status invalida: $atual -> $novoStatus");
    }

    consultar(
        'UPDATE protocolo SET status = ?, atualizado_em = NOW() WHERE id = ?',
        array($novoStatus, $p['id'])
    );

    $p = buscar_protocolo($params['numero']);
    responder(200, protocolo_para_array($p, false));
}
