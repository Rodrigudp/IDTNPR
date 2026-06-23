<?php

defined('IDTNPR') or exit('Acesso negado.');

const TIPOS_IMAGEM = array('image/png', 'image/jpeg', 'image/webp', 'image/gif');

function admin_upload_arquivo($params)
{
    exigir_admin();

    $dados = salvar_upload('arquivo', 'site', TIPOS_IMAGEM, 'PNG, JPG, WEBP, GIF');

    $linha = consultar(
        'INSERT INTO arquivos (arqnomeoriginal, arqcontenttype, arqtamanho, arqcaminho, arqinclusao)
         VALUES (?, ?, ?, ?, NOW())
         RETURNING *',
        array($dados['nome_original'], $dados['content_type'], $dados['tamanho_bytes'], $dados['caminho'])
    )->fetch();

    responder(201, array(
        'id'           => (int) $linha['arqid'],
        'nomeOriginal' => $linha['arqnomeoriginal'],
        'contentType'  => $linha['arqcontenttype'],
        'tamanhoBytes' => $linha['arqtamanho'] !== null ? (int) $linha['arqtamanho'] : null,
        'url'          => '/api/arquivos/' . (int) $linha['arqid'],
    ));
}

function baixar_arquivo($params)
{
    $id  = (int) $params['id'];
    $arq = consultar(
        'SELECT * FROM arquivos WHERE arqid = ? AND arqexclusao IS NULL',
        array($id)
    )->fetch();

    if (!$arq) {
        responder_erro(404, 'Arquivo nao encontrado: ' . $id);
    }

    $caminho = resolver_caminho($arq['arqcaminho']);
    if ($caminho === null || !is_file($caminho)) {
        responder_erro(404, 'Arquivo fisico ausente: ' . $arq['arqcaminho']);
    }

    $tipo = $arq['arqcontenttype'] ? $arq['arqcontenttype'] : 'application/octet-stream';

    header('Content-Type: ' . $tipo);
    header('Content-Disposition: inline; filename="' . basename($arq['arqnomeoriginal']) . '"');
    header('Content-Length: ' . filesize($caminho));
    readfile($caminho);
    exit;
}
