<?php
// =====================================================================
// index.php  —  PORTA DE ENTRADA DE TODA A API
// ---------------------------------------------------------------------
// Todas as requisições caem aqui (o .htaccess redireciona para cá).
// Este arquivo:
//   1) carrega os outros arquivos do sistema;
//   2) configura CORS e cabeçalhos de segurança;
//   3) descobre qual rota foi pedida e chama a função certa.
//
// Para entender uma rota específica, procure a função pelo nome na
// pasta "rotas/". A "tabela de rotas" lá embaixo é o mapa do sistema.
// =====================================================================

// Marca que estamos rodando "por dentro" do sistema (os outros arquivos
// checam isso para não poderem ser abertos direto pelo navegador).
define('IDTNPR', true);

// Em produção não mostramos erros do PHP na tela (evita vazar detalhes).
// Para depurar localmente, troque para '1' temporariamente.
ini_set('display_errors', '0');
error_reporting(E_ALL);

// ---- 1) Carrega configuração, bibliotecas e rotas ----
require __DIR__ . '/config.php';

require __DIR__ . '/lib/http.php';
require __DIR__ . '/lib/banco.php';
require __DIR__ . '/lib/validacao.php';
require __DIR__ . '/lib/jwt.php';
require __DIR__ . '/lib/auth.php';
require __DIR__ . '/lib/login.php';
require __DIR__ . '/lib/armazenamento.php';

require __DIR__ . '/rotas/auth.php';
require __DIR__ . '/rotas/protocolos.php';
require __DIR__ . '/rotas/conteudo.php';
require __DIR__ . '/rotas/contato.php';
require __DIR__ . '/rotas/arquivos.php';

// ---- 2) CORS e cabeçalhos de segurança ----
aplicar_cors();
header('X-Content-Type-Options: nosniff'); // o navegador não "adivinha" o tipo do conteúdo
header('X-Frame-Options: DENY');           // impede que o site seja embutido em iframes

$metodo  = $_SERVER['REQUEST_METHOD'];
$caminho = caminho_requisicao();

// Requisição de "verificação" do navegador (CORS): responde e encerra.
if ($metodo === 'OPTIONS') {
    responder_vazio(204);
}

// ---- 3) Tabela de rotas ----
// Formato:  rota(MÉTODO, CAMINHO, função_que_trata)
// O {algo} no caminho vira um parâmetro entregue para a função.
$jaTratou = false;

// Teste rápido para saber se a API está no ar.
rota('GET', '/api/health', function ($p) {
    responder(200, array('status' => 'ok'));
});

// --- Autenticação ---
rota('POST', '/api/auth/login', 'login');

// --- Protocolos (público) ---
rota('POST', '/api/protocolos',                 'abrir_protocolo');
rota('GET',  '/api/protocolos/{numero}',        'acompanhar_protocolo');
rota('POST', '/api/protocolos/{numero}/anexos', 'anexar_protocolo');

// --- Conteúdo do site (público) ---
rota('GET', '/api/conteudo', 'conteudo_landing');
rota('GET', '/api/projetos', 'listar_projetos_publico');

// --- Contato (público) ---
rota('POST', '/api/contato', 'enviar_contato');

// --- Arquivos (download público) ---
rota('GET', '/api/arquivos/{id}', 'baixar_arquivo');

// --- Protocolos (admin) ---
rota('GET',   '/api/admin/protocolos',                  'admin_listar_protocolos');
rota('GET',   '/api/admin/protocolos/{numero}',         'admin_detalhar_protocolo');
rota('PATCH', '/api/admin/protocolos/{numero}/status',  'admin_atualizar_status');

// --- Conteúdo do site (admin) ---
rota('GET',    '/api/admin/conteudo',      'admin_obter_conteudo');
rota('PUT',    '/api/admin/conteudo',      'admin_atualizar_conteudo');
rota('GET',    '/api/admin/projetos',      'admin_listar_projetos');
rota('POST',   '/api/admin/projetos',      'admin_criar_projeto');
rota('PUT',    '/api/admin/projetos/{id}', 'admin_atualizar_projeto');
rota('DELETE', '/api/admin/projetos/{id}', 'admin_remover_projeto');

// --- Contato (admin) ---
rota('GET',   '/api/admin/mensagens',            'admin_listar_mensagens');
rota('PATCH', '/api/admin/mensagens/{id}/lida',  'admin_marcar_mensagem_lida');

// --- Arquivos (upload admin) ---
rota('POST', '/api/admin/arquivos', 'admin_upload_arquivo');

// Se nenhuma rota acima atendeu, é 404.
if (!$jaTratou) {
    responder_erro(404, 'Rota nao encontrada: ' . $metodo . ' ' . $caminho);
}


// =====================================================================
// Funções do roteador (ficam aqui no fim para o início ficar limpo)
// =====================================================================

/**
 * Descobre o caminho pedido, sempre a partir de "/api/".
 * Funciona mesmo se o sistema estiver numa subpasta do site, pois
 * cortamos tudo que vem antes de "/api/".
 */
function caminho_requisicao()
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = rawurldecode($uri);

    $pos = strpos($uri, '/api/');
    if ($pos !== false) {
        $uri = substr($uri, $pos);
    }

    // Remove a barra final (menos se o caminho for só "/").
    if (strlen($uri) > 1) {
        $uri = rtrim($uri, '/');
    }
    return $uri;
}

/**
 * Compara a rota pedida com um padrão. Se baterem (mesmo método e mesmo
 * caminho), chama a função e marca que a requisição já foi tratada.
 * Trechos como "{numero}" no padrão viram parâmetros para a função.
 */
function rota($metodoEsperado, $padrao, $callback)
{
    global $jaTratou, $metodo, $caminho;

    if ($jaTratou || $metodo !== $metodoEsperado) {
        return;
    }

    $partesPadrao  = explode('/', trim($padrao, '/'));
    $partesCaminho = explode('/', trim($caminho, '/'));

    // Quantidade de "pedaços" diferente já significa que não é esta rota.
    if (count($partesPadrao) !== count($partesCaminho)) {
        return;
    }

    $params = array();
    for ($i = 0; $i < count($partesPadrao); $i++) {
        $pedaco = $partesPadrao[$i];

        if (strlen($pedaco) > 1 && $pedaco[0] === '{' && substr($pedaco, -1) === '}') {
            // É um parâmetro, ex.: {numero}. Guarda o valor recebido.
            $nome = substr($pedaco, 1, -1);
            $params[$nome] = $partesCaminho[$i];
        } elseif ($pedaco !== $partesCaminho[$i]) {
            // Pedaço fixo que não bate -> não é esta rota.
            return;
        }
    }

    $jaTratou = true;
    call_user_func($callback, $params);
}

/**
 * Libera o acesso do front-end (CORS). Só são liberadas as origens
 * listadas em CORS_ORIGINS no .env (separadas por vírgula).
 */
function aplicar_cors()
{
    $permitidas = array_map('trim', explode(',', (string) env('CORS_ORIGINS', '')));
    $origem     = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

    if ($origem !== '' && in_array($origem, $permitidas, true)) {
        header('Access-Control-Allow-Origin: ' . $origem);
        header('Vary: Origin');
        header('Access-Control-Allow-Credentials: true');
    }

    header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Authorization, Content-Type');
}
