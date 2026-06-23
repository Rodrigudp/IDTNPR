<?php

defined('IDTNPR') or exit('Acesso negado.');

function mapa_conteudo()
{
    return array(
        'heroTitle'       => 'constituloprincipal',
        'heroHighlight'   => 'consdestaqueprincipal',
        'heroDesc'        => 'consdescricaoprincipal',
        'heroBtn'         => 'constextobotao',
        'aboutText'       => 'conssobre',
        'feat1Title'      => 'consrecurso1titulo',
        'feat1Desc'       => 'consrecurso1descricao',
        'feat2Title'      => 'consrecurso2titulo',
        'feat2Desc'       => 'consrecurso2descricao',
        'feat3Title'      => 'consrecurso3titulo',
        'feat3Desc'       => 'consrecurso3descricao',
        'feat4Title'      => 'consrecurso4titulo',
        'feat4Desc'       => 'consrecurso4descricao',
        'ctaTitle'        => 'constitulochamada',
        'ctaDesc'         => 'consdescricaochamada',
        'ctaBtn'          => 'constextobotaochamada',
        'contatoEmail'    => 'consemailcontato',
        'contatoTelefone' => 'constelefonecontato',
        'logoUrl'         => 'conslogourl',
        'heroImgUrl'      => 'consimagemprincipalurl',
    );
}

function carregar_conteudo()
{
    $linha = consultar(
        'SELECT * FROM conteudos_site WHERE consexclusao IS NULL ORDER BY consid LIMIT 1'
    )->fetch();

    if (!$linha) {
        responder_erro(404, 'Conteudo do site nao inicializado.');
    }
    return $linha;
}

function conteudo_para_array($linha)
{
    $dto = array();
    foreach (mapa_conteudo() as $campoJson => $coluna) {
        $dto[$campoJson] = $linha[$coluna];
    }
    return $dto;
}

function projeto_para_array($p)
{
    return array(
        'id'        => (int) $p['prjid'],
        'titulo'    => $p['prjtitulo'],
        'descricao' => $p['prjdescricao'],
        'imagemUrl' => $p['prjimagemurl'],
        'link'      => $p['prjlink'],
        'ordem'     => (int) $p['prjordem'],
    );
}

function listar_projetos_ordenados()
{
    $linhas = consultar(
        'SELECT * FROM projetos WHERE prjexclusao IS NULL ORDER BY prjordem ASC, prjid ASC'
    )->fetchAll();
    return array_map('projeto_para_array', $linhas);
}

function conteudo_landing($params)
{
    responder(200, array(
        'conteudo' => conteudo_para_array(carregar_conteudo()),
        'projetos' => listar_projetos_ordenados(),
    ));
}

function listar_projetos_publico($params)
{
    responder(200, listar_projetos_ordenados());
}

function admin_obter_conteudo($params)
{
    exigir_admin();
    responder(200, conteudo_para_array(carregar_conteudo()));
}

function admin_atualizar_conteudo($params)
{
    exigir_admin();
    $dados = ler_json();
    $linha = carregar_conteudo();

    $colunas = array();
    $valores = array();
    foreach (mapa_conteudo() as $campoJson => $coluna) {
        $colunas[] = "$coluna = ?";
        $valores[] = isset($dados[$campoJson]) ? $dados[$campoJson] : null;
    }
    $valores[] = $linha['consid'];

    $sql = 'UPDATE conteudos_site SET ' . implode(', ', $colunas) . ' WHERE consid = ?';
    consultar($sql, $valores);

    responder(200, conteudo_para_array(carregar_conteudo()));
}

function admin_listar_projetos($params)
{
    exigir_admin();
    responder(200, listar_projetos_ordenados());
}

function ler_projeto_do_corpo()
{
    $dados = ler_json();

    $projeto = array(
        'titulo'    => v_texto($dados, 'titulo'),
        'descricao' => isset($dados['descricao']) ? (string) $dados['descricao'] : null,
        'imagemUrl' => v_texto($dados, 'imagemUrl'),
        'link'      => v_texto($dados, 'link'),
        'ordem'     => isset($dados['ordem']) ? (int) $dados['ordem'] : 0,
    );

    $erros = array();
    v_obrigatorio($erros, $projeto['titulo'], 'titulo');
    v_max($erros, $projeto['titulo'], 'titulo', 150);
    v_max($erros, $projeto['imagemUrl'], 'imagemUrl', 500);
    v_max($erros, $projeto['link'], 'link', 500);
    v_finalizar($erros);

    return $projeto;
}

function admin_criar_projeto($params)
{
    exigir_admin();
    $p = ler_projeto_do_corpo();

    $linha = consultar(
        'INSERT INTO projetos (prjtitulo, prjdescricao, prjimagemurl, prjlink, prjordem, prjinclusao)
         VALUES (?, ?, ?, ?, ?, NOW())
         RETURNING *',
        array($p['titulo'], $p['descricao'], $p['imagemUrl'], $p['link'], $p['ordem'])
    )->fetch();

    responder(201, projeto_para_array($linha));
}

function admin_atualizar_projeto($params)
{
    exigir_admin();
    $id = (int) $params['id'];

    $existe = consultar(
        'SELECT 1 FROM projetos WHERE prjid = ? AND prjexclusao IS NULL',
        array($id)
    )->fetch();
    if (!$existe) {
        responder_erro(404, 'Projeto nao encontrado: ' . $id);
    }

    $p = ler_projeto_do_corpo();
    $linha = consultar(
        'UPDATE projetos
         SET prjtitulo = ?, prjdescricao = ?, prjimagemurl = ?, prjlink = ?, prjordem = ?
         WHERE prjid = ?
         RETURNING *',
        array($p['titulo'], $p['descricao'], $p['imagemUrl'], $p['link'], $p['ordem'], $id)
    )->fetch();

    responder(200, projeto_para_array($linha));
}

function admin_remover_projeto($params)
{
    exigir_admin();
    $id = (int) $params['id'];

    $existe = consultar(
        'SELECT 1 FROM projetos WHERE prjid = ? AND prjexclusao IS NULL',
        array($id)
    )->fetch();
    if (!$existe) {
        responder_erro(404, 'Projeto nao encontrado: ' . $id);
    }

    consultar('UPDATE projetos SET prjexclusao = NOW() WHERE prjid = ?', array($id));
    responder_vazio(204);
}
