<?php

defined('IDTNPR') or exit('Acesso negado.');

const TIPOS_SOLICITACAO = array(
    'INFORMACAO', 'SOLICITACAO_SERVICO', 'RECLAMACAO', 'DENUNCIA', 'ELOGIO', 'OUTRO',
);

const STATUS_PROTOCOLO = array('ABERTO', 'EM_ANALISE', 'CONCLUIDO', 'ARQUIVADO');
const TIPOS_ANEXO = array('application/pdf', 'image/jpeg', 'image/png');

function transicoes_permitidas($status)
{
    switch ($status) {
        case 'ABERTO':     return array('EM_ANALISE', 'ARQUIVADO');
        case 'EM_ANALISE': return array('CONCLUIDO', 'ARQUIVADO');
        case 'CONCLUIDO':  return array('ARQUIVADO');
        default:           return array();
    }
}

function tipo_solicitacao_id($sigla)
{
    $linha = consultar(
        'SELECT tipid FROM tipos_solicitacao WHERE tipsigla = ? AND tipexclusao IS NULL AND tipativo = TRUE',
        array($sigla)
    )->fetch();

    if (!$linha) {
        responder_erro(400, 'Tipo de solicitacao nao cadastrado: ' . $sigla);
    }

    return (int) $linha['tipid'];
}

function status_solicitacao_id($sigla)
{
    $linha = consultar(
        'SELECT stsid FROM status_solicitacao WHERE stssigla = ? AND stsexclusao IS NULL AND stsativo = TRUE',
        array($sigla)
    )->fetch();

    if (!$linha) {
        responder_erro(400, 'Status de solicitacao nao cadastrado: ' . $sigla);
    }

    return (int) $linha['stsid'];
}

function gerar_numero_protocolo()
{
    do {
        $numero = date('Y') . '-' . strtoupper(bin2hex(random_bytes(12)));
        $existe = consultar('SELECT 1 FROM solicitacoes WHERE solnumero = ?', array($numero))->fetch();
    } while ($existe);
    return $numero;
}

function mascarar_cpf($cpf)
{
    if ($cpf === null || strlen($cpf) < 2) {
        return '***.***.***-**';
    }
    return '***.***.***-' . substr($cpf, -2);
}

function buscar_protocolo($numero)
{
    $p = consultar(
        'SELECT s.*, t.tipsigla AS tipo_solicitacao, st.stssigla AS status
         FROM solicitacoes s
         JOIN tipos_solicitacao t ON t.tipid = s.soltipoid
         JOIN status_solicitacao st ON st.stsid = s.solstatusid
         WHERE s.solnumero = ? AND s.solexclusao IS NULL',
        array($numero)
    )->fetch();

    if (!$p) {
        responder_erro(404, 'Protocolo nao encontrado: ' . $numero);
    }
    return $p;
}

function anexos_do_protocolo($solicitacaoId)
{
    return consultar(
        'SELECT * FROM anexos_solicitacoes WHERE anxsolicitacaoid = ? AND anxexclusao IS NULL ORDER BY anxid',
        array($solicitacaoId)
    )->fetchAll();
}

function anexo_para_array($a)
{
    return array(
        'id'           => (int) $a['anxid'],
        'nomeOriginal' => $a['anxnomeoriginal'],
        'contentType'  => $a['anxcontenttype'],
        'tamanhoBytes' => $a['anxtamanho'] !== null ? (int) $a['anxtamanho'] : null,
    );
}

function protocolo_para_array($p, $mascarar)
{
    $anexos = anexos_do_protocolo($p['solid']);

    return array(
        'numero'          => $p['solnumero'],
        'nome'            => $p['solnome'],
        'cpf'             => $mascarar ? mascarar_cpf($p['solcpf']) : $p['solcpf'],
        'email'           => $p['solemail'],
        'telefone'        => $p['soltelefone'],
        'tipoSolicitacao' => $p['tipo_solicitacao'],
        'descricao'       => $p['soldescricao'],
        'status'          => $p['status'],
        'criadoEm'        => data_iso($p['solinclusao']),
        'atualizadoEm'    => data_iso($p['solinclusao']),
        'anexos'          => array_map('anexo_para_array', $anexos),
    );
}

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
    $tipoId = tipo_solicitacao_id($tipo);
    $statusId = status_solicitacao_id('ABERTO');

    consultar(
        'INSERT INTO solicitacoes
            (solnumero, solnome, solcpf, solemail, soltelefone, soltipoid, solstatusid, soldescricao, solinclusao)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())',
        array($numero, $nome, $cpf, $email, $telefone, $tipoId, $statusId, $descricao)
    );

    $p = buscar_protocolo($numero);
    responder(201, protocolo_para_array($p, true));
}

function acompanhar_protocolo($params)
{
    $p = buscar_protocolo($params['numero']);
    responder(200, protocolo_para_array($p, true));
}

function anexar_protocolo($params)
{
    $p = buscar_protocolo($params['numero']);
    $dados = salvar_upload('arquivo', 'protocolos', TIPOS_ANEXO, 'PDF, JPG, PNG');

    consultar(
        'INSERT INTO anexos_solicitacoes
            (anxsolicitacaoid, anxnomeoriginal, anxcontenttype, anxtamanho, anxcaminho, anxinclusao)
         VALUES (?, ?, ?, ?, ?, NOW())',
        array($p['solid'], $dados['nome_original'], $dados['content_type'], $dados['tamanho_bytes'], $dados['caminho'])
    );

    $p = buscar_protocolo($params['numero']);
    responder(200, protocolo_para_array($p, true));
}

function admin_listar_protocolos($params)
{
    exigir_admin();

    $status = isset($_GET['status']) ? trim($_GET['status']) : '';
    $page   = max(0, (int) (isset($_GET['page']) ? $_GET['page'] : 0));
    $size   = (int) (isset($_GET['size']) ? $_GET['size'] : 20);
    if ($size < 1)   { $size = 20; }
    if ($size > 100) { $size = 100; }

    $where = ' WHERE s.solexclusao IS NULL';
    $args  = array();
    if ($status !== '') {
        if (!in_array($status, STATUS_PROTOCOLO, true)) {
            responder_erro(400, 'Status invalido. Opcoes: ' . implode(', ', STATUS_PROTOCOLO) . '.');
        }
        $where .= ' AND st.stssigla = ?';
        $args[] = $status;
    }

    $base = ' FROM solicitacoes s
              JOIN tipos_solicitacao t ON t.tipid = s.soltipoid
              JOIN status_solicitacao st ON st.stsid = s.solstatusid' . $where;

    $total = (int) consultar('SELECT COUNT(*)' . $base, $args)->fetchColumn();
    $offset = $page * $size;

    $linhas = consultar(
        'SELECT s.*, t.tipsigla AS tipo_solicitacao, st.stssigla AS status' . $base .
        ' ORDER BY s.solinclusao DESC LIMIT ' . $size . ' OFFSET ' . $offset,
        $args
    )->fetchAll();

    $conteudo = array();
    foreach ($linhas as $p) {
        $conteudo[] = protocolo_para_array($p, false);
    }

    responder(200, pagina($conteudo, $page, $size, $total));
}

function admin_detalhar_protocolo($params)
{
    exigir_admin();
    $p = buscar_protocolo($params['numero']);
    responder(200, protocolo_para_array($p, false));
}

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

    if ($atual !== $novoStatus && !in_array($novoStatus, transicoes_permitidas($atual), true)) {
        responder_erro(400, "Transicao de status invalida: $atual -> $novoStatus");
    }

    consultar(
        'UPDATE solicitacoes SET solstatusid = ? WHERE solid = ?',
        array(status_solicitacao_id($novoStatus), $p['solid'])
    );

    $p = buscar_protocolo($params['numero']);
    responder(200, protocolo_para_array($p, false));
}
