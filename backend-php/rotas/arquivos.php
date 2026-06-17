<?php
// =====================================================================
// rotas/arquivos.php
// ---------------------------------------------------------------------
// Upload (admin) e download (público) de imagens do site (logo, hero).
//
// Rota do admin (exige login):
//   POST /api/admin/arquivos   (campo do formulário: "arquivo")
//        -> devolve { id, nomeOriginal, contentType, tamanhoBytes, url }
// Rota pública:
//   GET /api/arquivos/{id}     -> baixa/exibe o arquivo
// =====================================================================

defined('IDTNPR') or exit('Acesso negado.');

// Tipos de imagem aceitos no upload do site.
// (Sem SVG de propósito: SVG pode conter código e causar problema de segurança.)
const TIPOS_IMAGEM = array('image/png', 'image/jpeg', 'image/webp', 'image/gif');

/** POST /api/admin/arquivos */
function admin_upload_arquivo($params)
{
    exigir_admin();

    $dados = salvar_upload('arquivo', 'site', TIPOS_IMAGEM, 'PNG, JPG, WEBP, GIF');

    consultar(
        'INSERT INTO arquivo (nome_original, content_type, tamanho_bytes, caminho, criado_em)
         VALUES (?, ?, ?, ?, NOW())',
        array($dados['nome_original'], $dados['content_type'], $dados['tamanho_bytes'], $dados['caminho'])
    );

    $id = (int) banco()->lastInsertId();

    responder(201, array(
        'id'           => $id,
        'nomeOriginal' => $dados['nome_original'],
        'contentType'  => $dados['content_type'],
        'tamanhoBytes' => $dados['tamanho_bytes'],
        'url'          => '/api/arquivos/' . $id, // endereço público para baixar
    ));
}

/** GET /api/arquivos/{id} */
function baixar_arquivo($params)
{
    $id  = (int) $params['id'];
    $arq = consultar('SELECT * FROM arquivo WHERE id = ?', array($id))->fetch();

    if (!$arq) {
        responder_erro(404, 'Arquivo nao encontrado: ' . $id);
    }

    $caminho = resolver_caminho($arq['caminho']);
    if ($caminho === null || !is_file($caminho)) {
        responder_erro(404, 'Arquivo fisico ausente: ' . $arq['caminho']);
    }

    $tipo = $arq['content_type'] ? $arq['content_type'] : 'application/octet-stream';

    // Aqui NÃO usamos responder(): enviamos o arquivo "cru", não um JSON.
    header('Content-Type: ' . $tipo);
    header('Content-Disposition: inline; filename="' . basename($arq['nome_original']) . '"');
    header('Content-Length: ' . filesize($caminho));
    readfile($caminho);
    exit;
}
