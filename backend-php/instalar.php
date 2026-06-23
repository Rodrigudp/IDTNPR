<?php

define('IDTNPR', true);

require __DIR__ . '/config.php';
require __DIR__ . '/lib/http.php';
require __DIR__ . '/lib/banco.php';

function passo($texto, $ok = true)
{
    echo '<p style="font-family:Arial,sans-serif;color:' . ($ok ? '#14532d' : '#991b1b') . '">';
    echo ($ok ? 'OK - ' : 'ERRO - ') . htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
    echo '</p>';
    if (!$ok) {
        exit;
    }
}

echo '<h1 style="font-family:Arial,sans-serif">Instalacao IDTNPR</h1>';

try {
    banco();
    passo('Conexao com PostgreSQL realizada.');
} catch (Throwable $e) {
    passo('Falha ao conectar no PostgreSQL: ' . $e->getMessage(), false);
}

try {
    consultar(
        "INSERT INTO tipos_solicitacao (tipnome, tipsigla, tipativo, tipinclusao)
         VALUES
            ('Informacao', 'INFORMACAO', TRUE, NOW()),
            ('Solicitacao de servico', 'SOLICITACAO_SERVICO', TRUE, NOW()),
            ('Reclamacao', 'RECLAMACAO', TRUE, NOW()),
            ('Denuncia', 'DENUNCIA', TRUE, NOW()),
            ('Elogio', 'ELOGIO', TRUE, NOW()),
            ('Outro', 'OUTRO', TRUE, NOW())
         ON CONFLICT (tipsigla) DO NOTHING"
    );
    passo('Tipos de solicitacao conferidos.');
} catch (Throwable $e) {
    passo('Falha ao criar tipos de solicitacao: ' . $e->getMessage(), false);
}

try {
    consultar(
        "INSERT INTO status_solicitacao (stsnome, stssigla, stsordem, stsativo, stsinclusao)
         VALUES
            ('Aberto', 'ABERTO', 1, TRUE, NOW()),
            ('Em analise', 'EM_ANALISE', 2, TRUE, NOW()),
            ('Concluido', 'CONCLUIDO', 3, TRUE, NOW()),
            ('Arquivado', 'ARQUIVADO', 4, TRUE, NOW())
         ON CONFLICT (stssigla) DO NOTHING"
    );
    passo('Status de solicitacao conferidos.');
} catch (Throwable $e) {
    passo('Falha ao criar status de solicitacao: ' . $e->getMessage(), false);
}

try {
    $temConteudo = consultar(
        'SELECT 1 FROM conteudos_site WHERE consexclusao IS NULL LIMIT 1'
    )->fetch();

    if (!$temConteudo) {
        consultar(
            "INSERT INTO conteudos_site (
                constituloprincipal, consdestaqueprincipal, consdescricaoprincipal, constextobotao,
                conssobre, consrecurso1titulo, consrecurso1descricao, consrecurso2titulo,
                consrecurso2descricao, consrecurso3titulo, consrecurso3descricao,
                consrecurso4titulo, consrecurso4descricao, constitulochamada,
                consdescricaochamada, constextobotaochamada, consemailcontato,
                constelefonecontato, consinclusao
             ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
            array(
                'Seu municipio pode fazer parte do',
                'ecossistema de inovacao',
                'Desenvolvemos solucoes digitais para modernizar servicos publicos, promover transparencia e aproximar cidadaos do governo atraves da tecnologia.',
                'Solicitar Reuniao Tecnica',
                'Atuamos na transformacao digital do setor publico, combinando tecnologia, gestao e conhecimento para gerar resultados reais para a sociedade.',
                'Transformacao Digital Publica',
                'Solucoes digitais para modernizar servicos e processos publicos.',
                'Dados e Transparencia',
                'Inteligencia de dados para decisoes mais eficientes.',
                'Inovacao e Gestao',
                'Metodologias que aumentam a eficiencia dos orgaos publicos.',
                'Capacitacao Tecnica',
                'Formacao e transferencia de conhecimento para equipes.',
                'Parcerias que transformam cidades.',
                'Trabalhamos junto a orgaos publicos, instituicoes e especialistas para transformar realidades e gerar impacto positivo.',
                'QUERO SER PARCEIRO',
                'contato@idtnpr.org.br',
                '(44) 99999-9999'
            )
        );
    }
    passo('Conteudo inicial conferido.');
} catch (Throwable $e) {
    passo('Falha ao criar conteudo inicial: ' . $e->getMessage(), false);
}

$emailAdmin = env('ADMIN_EMAIL', 'admin@idtnpr.org.br');
$senhaAdmin = env('ADMIN_SENHA', '');
$nomeAdmin  = env('ADMIN_NOME', 'Administrador IDTNPR');

if ($senhaAdmin === '' || $senhaAdmin === 'defina-uma-senha-forte') {
    passo('Defina ADMIN_SENHA no arquivo .env antes de instalar.', false);
}

try {
    $ja = consultar(
        'SELECT usuid FROM usuarios WHERE usuemail = ? AND usuexclusao IS NULL',
        array($emailAdmin)
    )->fetch();

    if ($ja) {
        passo('Usuario admin ja existe: ' . $emailAdmin);
    } else {
        consultar(
            'INSERT INTO usuarios (usunome, usuemail, ususenha, usuinclusao) VALUES (?, ?, ?, NOW())',
            array($nomeAdmin, $emailAdmin, password_hash($senhaAdmin, PASSWORD_BCRYPT))
        );
        passo('Usuario admin criado: ' . $emailAdmin);
    }
} catch (Throwable $e) {
    passo('Falha ao criar usuario admin: ' . $e->getMessage(), false);
}

echo '<p style="font-family:Arial,sans-serif"><strong>Instalacao concluida.</strong></p>';
echo '<p style="font-family:Arial,sans-serif">Por seguranca, apague o arquivo <code>instalar.php</code> depois de usar.</p>';
