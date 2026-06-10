<?php
// =====================================================================
// instalar.php  —  RODE UMA VEZ para preparar o sistema
// ---------------------------------------------------------------------
// O que ele faz:
//   1) Cria as tabelas do banco (lendo o arquivo banco.sql);
//   2) Insere o conteúdo inicial do site;
//   3) Cria o usuário administrador (com a senha do .env, já criptografada).
//
// Como usar:
//   - Configure o arquivo .env (copie de .env.example) com os dados do
//     banco da Locaweb e a senha do admin (ADMIN_SENHA).
//   - Abra este arquivo no navegador:  https://seusite.com.br/api/instalar.php
//   - Quando aparecer "Instalacao concluida", APAGUE este arquivo do servidor.
//
// Pode rodar mais de uma vez sem problema (não duplica nada).
// =====================================================================

define('IDTNPR', true);
require __DIR__ . '/config.php';

header('Content-Type: text/html; charset=utf-8');

// Pequena função para imprimir uma linha de status na tela.
function passo($texto, $ok = true)
{
    $cor = $ok ? '#15803d' : '#b91c1c';
    $marca = $ok ? 'OK ' : 'ERRO ';
    echo '<p style="font-family:monospace;margin:4px 0;color:' . $cor . '">'
        . $marca . htmlspecialchars($texto) . '</p>';
}

echo '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="utf-8">'
    . '<title>Instalacao - IDTNPR</title></head><body style="max-width:760px;margin:40px auto;'
    . 'font-family:sans-serif;color:#0f172a;line-height:1.5">';
echo '<h1>Instalação do back-end IDTNPR</h1>';

// ---- 1) Conecta no banco ----
try {
    $pdo = new PDO(
        'mysql:host=' . env('DB_HOST', 'localhost')
            . ';port=' . env('DB_PORT', '3306')
            . ';dbname=' . env('DB_NAME', 'idtnpr')
            . ';charset=utf8mb4',
        env('DB_USER', 'root'),
        env('DB_PASSWORD', ''),
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
    passo('Conectado ao banco de dados.');
} catch (PDOException $e) {
    passo('Nao foi possivel conectar ao banco. Confira DB_HOST, DB_NAME, DB_USER e DB_PASSWORD no .env.', false);
    passo('Detalhe: ' . $e->getMessage(), false);
    exit;
}

// ---- 2) Cria as tabelas e o conteúdo inicial (a partir do banco.sql) ----
$sql = file_get_contents(__DIR__ . '/banco.sql');
if ($sql === false) {
    passo('Arquivo banco.sql nao encontrado.', false);
    exit;
}

// Remove o "BOM" (marca invisível que alguns editores, como o Bloco de Notas,
// colocam no início do arquivo) — senão ele atrapalharia o primeiro comando.
$sql = preg_replace('/^\xEF\xBB\xBF/', '', $sql);

try {
    // Remove as linhas que são só comentário (começam com --), para o
    // arquivo ser dividido em comandos de forma limpa.
    $linhas = preg_split('/\r\n|\r|\n/', $sql);
    $limpo = array();
    foreach ($linhas as $linha) {
        if (preg_match('/^\s*--/', $linha)) {
            continue;
        }
        $limpo[] = $linha;
    }
    $sqlLimpo = implode("\n", $limpo);

    // Cada comando SQL termina em ";". Executa um por um.
    foreach (explode(';', $sqlLimpo) as $comando) {
        if (trim($comando) === '') {
            continue;
        }
        $pdo->exec($comando);
    }
    passo('Tabelas e conteudo inicial criados (ou ja existiam).');
} catch (PDOException $e) {
    passo('Falha ao criar as tabelas: ' . $e->getMessage(), false);
    exit;
}

// ---- 3) Cria o usuário administrador ----
$nomeAdmin  = env('ADMIN_NOME', 'Administrador IDTNPR');
$emailAdmin = env('ADMIN_EMAIL', 'admin@idtnpr.org.br');
$senhaAdmin = env('ADMIN_SENHA');

if (!$senhaAdmin) {
    passo('Defina ADMIN_SENHA no arquivo .env antes de instalar.', false);
    exit;
}

try {
    $ja = $pdo->prepare('SELECT id FROM usuario WHERE email = ?');
    $ja->execute(array($emailAdmin));

    if ($ja->fetch()) {
        passo('Usuario admin ja existe: ' . $emailAdmin . ' (nada a fazer).');
    } else {
        // password_hash gera o mesmo tipo de hash (BCrypt) usado pelo back-end antigo.
        $hash = password_hash($senhaAdmin, PASSWORD_BCRYPT);
        $ins = $pdo->prepare(
            'INSERT INTO usuario (nome, email, senha, role, enabled) VALUES (?, ?, ?, ?, 1)'
        );
        $ins->execute(array($nomeAdmin, $emailAdmin, $hash, 'ADMIN'));
        passo('Usuario admin criado: ' . $emailAdmin);
    }
} catch (PDOException $e) {
    passo('Falha ao criar o usuario admin: ' . $e->getMessage(), false);
    exit;
}

echo '<h2 style="color:#15803d">Instalação concluída!</h2>';
echo '<p><strong>Importante:</strong> por segurança, <strong>APAGUE o arquivo instalar.php</strong> '
    . 'do servidor agora. Ele não é mais necessário.</p>';
echo '<p>Teste a API abrindo: <code>/api/health</code> (deve responder <code>{"status":"ok"}</code>).</p>';
echo '</body></html>';
