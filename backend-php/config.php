<?php
// =====================================================================
// config.php
// ---------------------------------------------------------------------
// Lê o arquivo ".env" e disponibiliza as configurações do sistema.
// Assim ninguém precisa procurar senhas espalhadas pelo código: tudo
// fica num único arquivo ".env" (que NÃO vai para o Git).
// =====================================================================

// Impede que este arquivo seja aberto diretamente pelo navegador.
defined('IDTNPR') or exit('Acesso negado.');

/**
 * Lê o arquivo .env (se existir) e joga cada variável para o ambiente.
 * Exemplo de linha no .env:  DB_PASSWORD=minhasenha
 */
function carregar_env($caminho)
{
    if (!is_file($caminho)) {
        // Sem arquivo .env (ex.: produção usando variáveis do servidor). Tudo bem.
        return;
    }

    foreach (file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linha) {
        $linha = trim($linha);

        // Ignora linhas em branco e comentários (que começam com #).
        if ($linha === '' || $linha[0] === '#') {
            continue;
        }

        // Quebra a linha em "CHAVE" e "VALOR" no primeiro sinal de igual.
        $partes = explode('=', $linha, 2);
        if (count($partes) < 2) {
            continue;
        }

        $chave = trim($partes[0]);
        $valor = trim($partes[1]);

        // Remove aspas em volta do valor, se houver (ex.: CHAVE="meu valor").
        $valor = trim($valor, "\"'");

        putenv("$chave=$valor");
        $_ENV[$chave] = $valor;
    }
}

/**
 * Lê uma configuração pelo nome, com um valor padrão caso ela não exista.
 * Exemplo:  env('JWT_EXP_MINUTES', 480)
 */
function env($chave, $padrao = null)
{
    $valor = getenv($chave);
    if ($valor === false || $valor === '') {
        return $padrao;
    }
    return $valor;
}

// Carrega o .env que fica na mesma pasta deste arquivo.
carregar_env(__DIR__ . '/.env');
