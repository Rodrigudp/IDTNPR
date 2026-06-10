<?php
// =====================================================================
// lib/armazenamento.php
// ---------------------------------------------------------------------
// Grava e lê arquivos enviados (anexos de protocolo e imagens do site).
// Os arquivos ficam na pasta "uploads"; no banco guardamos só o caminho.
//
// Segurança aplicada aqui:
//  - O tipo do arquivo é detectado pelo CONTEÚDO real (não confiamos no
//    que o navegador diz), usando a extensão "fileinfo" do PHP.
//  - Cada arquivo ganha um nome aleatório (evita colisão e adivinhação).
//  - Tamanho máximo de 10 MB por arquivo.
// =====================================================================

defined('IDTNPR') or exit('Acesso negado.');

const UPLOAD_MAX_BYTES = 10485760; // 10 MB

/**
 * Descobre (e cria, se preciso) a pasta raiz dos uploads.
 * Por padrão é a pasta "uploads" ao lado do sistema; pode ser trocada
 * pela variável STORAGE_DIR no .env.
 */
function pasta_uploads()
{
    $dir = env('STORAGE_DIR', '');

    if ($dir === '') {
        // dirname(__DIR__) = a pasta "backend-php" (um nível acima de "lib").
        $dir = dirname(__DIR__) . '/uploads';
    } elseif ($dir[0] !== '/' && !preg_match('/^[A-Za-z]:/', $dir)) {
        // Caminho relativo no .env -> resolve a partir da pasta do sistema.
        $dir = dirname(__DIR__) . '/' . ltrim($dir, './');
    }

    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    return rtrim($dir, '/\\');
}

/**
 * Descobre o tipo (MIME) real de um arquivo, olhando o conteúdo dele.
 * Ex.: "application/pdf", "image/png".
 */
function tipo_mime($caminhoArquivo)
{
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $tipo  = finfo_file($finfo, $caminhoArquivo);
        finfo_close($finfo);
        return $tipo ? $tipo : 'application/octet-stream';
    }
    if (function_exists('mime_content_type')) {
        $tipo = mime_content_type($caminhoArquivo);
        return $tipo ? $tipo : 'application/octet-stream';
    }
    return 'application/octet-stream';
}

/**
 * Salva um arquivo recebido num formulário (campo $campo, ex.: "arquivo").
 * Valida o tipo contra $tiposPermitidos e devolve um array com os dados
 * para gravar no banco (caminho relativo, nome original, tipo, tamanho).
 *
 * $listaAmigavel é só o texto da mensagem de erro (ex.: "PDF, JPG, PNG").
 */
function salvar_upload($campo, $subpasta, $tiposPermitidos, $listaAmigavel)
{
    if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] === UPLOAD_ERR_NO_FILE) {
        responder_erro(400, 'Arquivo vazio.');
    }

    $arquivo = $_FILES[$campo];

    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        responder_erro(400, 'Falha no envio do arquivo (codigo ' . $arquivo['error'] . ').');
    }
    if ($arquivo['size'] <= 0) {
        responder_erro(400, 'Arquivo vazio.');
    }
    if ($arquivo['size'] > UPLOAD_MAX_BYTES) {
        responder_erro(400, 'Arquivo muito grande. Tamanho maximo: 10 MB.');
    }

    // Confia no conteúdo real do arquivo, não no que o navegador declarou.
    $tipoReal = tipo_mime($arquivo['tmp_name']);
    if (!in_array($tipoReal, $tiposPermitidos, true)) {
        responder_erro(400, 'Tipo de arquivo nao permitido. Aceitos: ' . $listaAmigavel . '.');
    }

    $pasta = pasta_uploads() . '/' . $subpasta;
    if (!is_dir($pasta)) {
        mkdir($pasta, 0775, true);
    }

    // Nome físico aleatório, mantendo a extensão original.
    $extensao   = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
    $nomeFisico = bin2hex(random_bytes(16)) . ($extensao !== '' ? '.' . $extensao : '');
    $destino    = $pasta . '/' . $nomeFisico;

    if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
        responder_erro(500, 'Nao foi possivel salvar o arquivo.');
    }

    return array(
        'caminho'       => $subpasta . '/' . $nomeFisico, // caminho relativo (vai para o banco)
        'nome_original' => $arquivo['name'],
        'content_type'  => $tipoReal,
        'tamanho_bytes' => (int) $arquivo['size'],
    );
}

/**
 * Transforma um caminho relativo (do banco) no caminho absoluto do arquivo,
 * garantindo que ele está mesmo dentro da pasta de uploads (proteção contra
 * tentativas de acessar outros arquivos do servidor com "../").
 * Devolve o caminho absoluto, ou null se não existir / for inválido.
 */
function resolver_caminho($relativo)
{
    $raiz     = realpath(pasta_uploads());
    $absoluto = realpath($raiz . '/' . $relativo);

    if ($raiz === false || $absoluto === false) {
        return null;
    }
    if (strpos($absoluto, $raiz) !== 0) {
        return null; // tentou sair da pasta de uploads
    }
    return $absoluto;
}
