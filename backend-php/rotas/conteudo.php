<?php
// =====================================================================
// rotas/conteudo.php
// ---------------------------------------------------------------------
// Conteúdo editável do site: os TEXTOS (seção hero, sobre, etc.) e os
// PROJETOS em destaque. Substitui o "localStorage" do admin.html antigo.
//
// Rotas públicas (a landing page usa):
//   GET /api/conteudo   -> { conteudo: {...textos...}, projetos: [...] }
//   GET /api/projetos   -> [ ...projetos... ]
// Rotas do admin (exigem login):
//   GET    /api/admin/conteudo        -> ler textos
//   PUT    /api/admin/conteudo        -> salvar textos
//   GET    /api/admin/projetos        -> listar projetos
//   POST   /api/admin/projetos        -> criar projeto
//   PUT    /api/admin/projetos/{id}   -> editar projeto
//   DELETE /api/admin/projetos/{id}   -> remover projeto
// =====================================================================

defined('IDTNPR') or exit('Acesso negado.');

/**
 * Liga cada campo do JSON (em "camelCase") à sua coluna no banco (em
 * "snake_case"). Assim não precisamos repetir a lista de 20 campos.
 */
function mapa_conteudo()
{
    return array(
        'heroTitle'       => 'hero_title',
        'heroHighlight'   => 'hero_highlight',
        'heroDesc'        => 'hero_desc',
        'heroBtn'         => 'hero_btn',
        'aboutText'       => 'about_text',
        'feat1Title'      => 'feat1_title',
        'feat1Desc'       => 'feat1_desc',
        'feat2Title'      => 'feat2_title',
        'feat2Desc'       => 'feat2_desc',
        'feat3Title'      => 'feat3_title',
        'feat3Desc'       => 'feat3_desc',
        'feat4Title'      => 'feat4_title',
        'feat4Desc'       => 'feat4_desc',
        'ctaTitle'        => 'cta_title',
        'ctaDesc'         => 'cta_desc',
        'ctaBtn'          => 'cta_btn',
        'contatoEmail'    => 'contato_email',
        'contatoTelefone' => 'contato_telefone',
        'logoUrl'         => 'logo_url',
        'heroImgUrl'      => 'hero_img_url',
    );
}

/** Lê o registro único de conteúdo do site (sempre id = 1). */
function carregar_conteudo()
{
    $linha = consultar('SELECT * FROM conteudo_site WHERE id = 1')->fetch();
    if (!$linha) {
        responder_erro(404, 'Conteudo do site nao inicializado. Rode o instalar.php.');
    }
    return $linha;
}

/** Transforma a linha do banco no JSON de textos (camelCase). */
function conteudo_para_array($linha)
{
    $dto = array();
    foreach (mapa_conteudo() as $campoJson => $coluna) {
        $dto[$campoJson] = $linha[$coluna];
    }
    return $dto;
}

/** Transforma a linha do banco no JSON de um projeto. */
function projeto_para_array($p)
{
    return array(
        'id'        => (int) $p['id'],
        'titulo'    => $p['titulo'],
        'descricao' => $p['descricao'],
        'imagemUrl' => $p['imagem_url'],
        'link'      => $p['link'],
        'ordem'     => (int) $p['ordem'],
    );
}

/** Lista os projetos já ordenados pelo campo "ordem". */
function listar_projetos_ordenados()
{
    $linhas = consultar('SELECT * FROM projeto ORDER BY ordem ASC, id ASC')->fetchAll();
    return array_map('projeto_para_array', $linhas);
}

// ---------------------------------------------------------------------
// Rotas públicas
// ---------------------------------------------------------------------

/** GET /api/conteudo */
function conteudo_landing($params)
{
    responder(200, array(
        'conteudo' => conteudo_para_array(carregar_conteudo()),
        'projetos' => listar_projetos_ordenados(),
    ));
}

/** GET /api/projetos */
function listar_projetos_publico($params)
{
    responder(200, listar_projetos_ordenados());
}

// ---------------------------------------------------------------------
// Rotas do admin (exigem login)
// ---------------------------------------------------------------------

/** GET /api/admin/conteudo */
function admin_obter_conteudo($params)
{
    exigir_admin();
    responder(200, conteudo_para_array(carregar_conteudo()));
}

/** PUT /api/admin/conteudo  (recebe os textos e salva) */
function admin_atualizar_conteudo($params)
{
    exigir_admin();
    $dados = ler_json();

    // Monta o "SET coluna = ?" para cada campo, pegando o valor do JSON.
    $colunas = array();
    $valores = array();
    foreach (mapa_conteudo() as $campoJson => $coluna) {
        $colunas[] = "$coluna = ?";
        $valores[] = isset($dados[$campoJson]) ? $dados[$campoJson] : null;
    }

    $sql = 'UPDATE conteudo_site SET ' . implode(', ', $colunas) . ', atualizado_em = NOW() WHERE id = 1';
    consultar($sql, $valores);

    responder(200, conteudo_para_array(carregar_conteudo()));
}

/** GET /api/admin/projetos */
function admin_listar_projetos($params)
{
    exigir_admin();
    responder(200, listar_projetos_ordenados());
}

/** Lê e valida os dados de um projeto enviados no corpo da requisição. */
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

/** POST /api/admin/projetos */
function admin_criar_projeto($params)
{
    exigir_admin();
    $p = ler_projeto_do_corpo();

    consultar(
        'INSERT INTO projeto (titulo, descricao, imagem_url, link, ordem, criado_em, atualizado_em)
         VALUES (?, ?, ?, ?, ?, NOW(), NOW())',
        array($p['titulo'], $p['descricao'], $p['imagemUrl'], $p['link'], $p['ordem'])
    );

    $id    = (int) banco()->lastInsertId();
    $linha = consultar('SELECT * FROM projeto WHERE id = ?', array($id))->fetch();
    responder(201, projeto_para_array($linha));
}

/** PUT /api/admin/projetos/{id} */
function admin_atualizar_projeto($params)
{
    exigir_admin();
    $id = (int) $params['id'];

    $existe = consultar('SELECT 1 FROM projeto WHERE id = ?', array($id))->fetch();
    if (!$existe) {
        responder_erro(404, 'Projeto nao encontrado: ' . $id);
    }

    $p = ler_projeto_do_corpo();
    consultar(
        'UPDATE projeto SET titulo = ?, descricao = ?, imagem_url = ?, link = ?, ordem = ?, atualizado_em = NOW()
         WHERE id = ?',
        array($p['titulo'], $p['descricao'], $p['imagemUrl'], $p['link'], $p['ordem'], $id)
    );

    $linha = consultar('SELECT * FROM projeto WHERE id = ?', array($id))->fetch();
    responder(200, projeto_para_array($linha));
}

/** DELETE /api/admin/projetos/{id} */
function admin_remover_projeto($params)
{
    exigir_admin();
    $id = (int) $params['id'];

    $existe = consultar('SELECT 1 FROM projeto WHERE id = ?', array($id))->fetch();
    if (!$existe) {
        responder_erro(404, 'Projeto nao encontrado: ' . $id);
    }

    consultar('DELETE FROM projeto WHERE id = ?', array($id));
    responder_vazio(204); // 204 = sucesso, sem conteúdo na resposta
}
