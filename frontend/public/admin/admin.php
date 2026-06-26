<?php
$dataFile = __DIR__ . '/site-data.json';
$uploadDir = __DIR__ . '/uploads';
$uploadUrl = 'uploads';

$defaultData = [
    'textos' => [
        'heroTitle' => 'Seu município pode fazer parte do',
        'heroHighlight' => 'ecossistema de inovação',
        'heroDesc' => 'Desenvolvemos soluções digitais para modernizar serviços públicos, promover transparência e aproximar cidadãos do governo através da tecnologia.',
        'heroBtn' => 'Solicitar Reunião Técnica',
        'aboutText' => 'Atuamos na transformação digital do setor público, combinando tecnologia, gestão e conhecimento para gerar resultados reais para a sociedade.',
        'feat1Title' => 'Transformação Digital Pública',
        'feat1Desc' => 'Soluções digitais para modernizar serviços e processos públicos.',
        'feat2Title' => 'Dados e Transparência',
        'feat2Desc' => 'Inteligência de dados para decisões mais eficientes.',
        'feat3Title' => 'Inovação e Gestão',
        'feat3Desc' => 'Metodologias que aumentam a eficiência dos órgãos públicos.',
        'feat4Title' => 'Capacitação Técnica',
        'feat4Desc' => 'Formação e transferência de conhecimento para equipes.',
        'ctaTitle' => 'Parcerias que transformam cidades.',
        'ctaDesc' => 'Trabalhamos junto a órgãos públicos, instituições e especialistas para transformar realidades e gerar impacto positivo.',
        'ctaBtn' => 'QUERO SER PARCEIRO →',
    ],
    'projetos' => [
        [
            'titulo' => 'Protocolo Digital',
            'descricao' => 'Plataforma para abertura, tramitação e acompanhamento de solicitações.',
            'imagem' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?q=80&w=800&auto=format&fit=crop',
            'link' => '#',
        ],
        [
            'titulo' => 'Atendimento ao Cidadão',
            'descricao' => 'Centralização de canais de atendimento com mais agilidade.',
            'imagem' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800&auto=format&fit=crop',
            'link' => '#',
        ],
        [
            'titulo' => 'Painel de Indicadores',
            'descricao' => 'Dashboards inteligentes para acompanhamento da gestão.',
            'imagem' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800&auto=format&fit=crop',
            'link' => '#',
        ],
    ],
    'contato' => [
        'email' => 'contato@idtnpr.org.br',
        'phone' => '(44) 99999-9999',
    ],
    'imagens' => [
        'logo' => '',
        'hero' => '',
    ],
];

function deepMerge(array $default, array $custom): array
{
    foreach ($custom as $key => $value) {
        if (
            isset($default[$key])
            && is_array($default[$key])
            && is_array($value)
            && !array_is_list($default[$key])
        ) {
            $default[$key] = deepMerge($default[$key], $value);
        } else {
            $default[$key] = $value;
        }
    }

    return $default;
}

function jsonResponse(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function loadSiteData(string $dataFile, array $defaultData): array
{
    if (!file_exists($dataFile)) {
        return $defaultData;
    }

    $content = file_get_contents($dataFile);

    if ($content === false || trim($content) === '') {
        return $defaultData;
    }

    $savedData = json_decode($content, true);

    if (!is_array($savedData)) {
        return $defaultData;
    }

    return deepMerge($defaultData, $savedData);
}

$action = $_GET['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'save') {
    $payload = json_decode(file_get_contents('php://input'), true);

    if (!is_array($payload)) {
        jsonResponse([
            'ok' => false,
            'message' => 'JSON inválido.',
        ], 400);
    }

    $payload = deepMerge($defaultData, $payload);

    $saved = file_put_contents(
        $dataFile,
        json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        LOCK_EX
    );

    if ($saved === false) {
        jsonResponse([
            'ok' => false,
            'message' => 'Não foi possível salvar o arquivo site-data.json. Verifique as permissões da pasta.',
        ], 500);
    }

    jsonResponse([
        'ok' => true,
        'message' => 'Alterações publicadas com sucesso!',
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'upload') {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        jsonResponse([
            'ok' => false,
            'message' => 'Falha ao enviar imagem.',
        ], 400);
    }

    if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
        jsonResponse([
            'ok' => false,
            'message' => 'Imagem muito grande. Limite máximo: 5 MB.',
        ], 400);
    }

    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        jsonResponse([
            'ok' => false,
            'message' => 'Não foi possível criar a pasta uploads.',
        ], 500);
    }

    $tmpName = $_FILES['image']['tmp_name'];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmpName);

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
        'image/svg+xml' => 'svg',
    ];

    if (!isset($allowed[$mime])) {
        jsonResponse([
            'ok' => false,
            'message' => 'Formato inválido. Use JPG, PNG, WEBP, GIF ou SVG.',
        ], 400);
    }

    $filename = date('YmdHis') . '-' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
    $destination = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($tmpName, $destination)) {
        jsonResponse([
            'ok' => false,
            'message' => 'Não foi possível salvar a imagem enviada.',
        ], 500);
    }

    jsonResponse([
        'ok' => true,
        'url' => $uploadUrl . '/' . $filename,
        'filename' => $filename,
    ]);
}

$siteData = loadSiteData($dataFile, $defaultData);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>IDTNPR - Painel Administrativo</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/admin.css"/>

</head>

<body>
<aside class="sidebar">
  <div class="sidebar-header">
    <h1>IDTNPR</h1>
    <span>Painel Administrativo</span>
  </div>

  <nav class="sidebar-nav">
    <button class="nav-item active" data-section="textos">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M4 7V4h16v3M9 20h6M12 4v16"/>
      </svg>
      <span>Textos</span>
    </button>

    <button class="nav-item" data-section="projetos">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="3" width="7" height="7" rx="1"/>
        <rect x="14" y="3" width="7" height="7" rx="1"/>
        <rect x="3" y="14" width="7" height="7" rx="1"/>
        <rect x="14" y="14" width="7" height="7" rx="1"/>
      </svg>
      <span>Projetos</span>
    </button>

    <button class="nav-item" data-section="contato">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
      </svg>
      <span>Contato</span>
    </button>

    <button class="nav-item" data-section="imagens">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="3" y="3" width="18" height="18" rx="2"/>
        <circle cx="8.5" cy="8.5" r="1.5"/>
        <path d="M21 15l-5-5L5 21"/>
      </svg>
      <span>Imagens</span>
    </button>
  </nav>

  <div class="sidebar-footer">
    <button class="btn-publish" id="btn-publicar">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
      </svg>
      Publicar Alterações
    </button>
  </div>
</aside>

<main class="main-content">
  <div class="section active" id="sec-textos">
    <div class="page-header">
      <h2>Textos do Site</h2>
      <p>Edite os textos exibidos nas seções da landing page</p>
    </div>

    <div class="card">
      <div class="card-title">Seção Hero</div>

      <div class="form-group">
        <label>Título Principal</label>
        <input type="text" id="hero-title">
      </div>

      <div class="form-group">
        <label>Destaque texto verde</label>
        <input type="text" id="hero-highlight">
      </div>

      <div class="form-group">
        <label>Descrição</label>
        <textarea id="hero-desc" rows="3"></textarea>
      </div>

      <div class="form-group">
        <label>Texto do Botão</label>
        <input type="text" id="hero-btn">
      </div>
    </div>

    <div class="card">
      <div class="card-title">Seção Sobre</div>

      <div class="form-group">
        <label>Texto Principal</label>
        <textarea id="about-text" rows="3"></textarea>
      </div>

      <div class="form-group">
        <label>Feature 1 - Título</label>
        <input type="text" id="feat1-title">
      </div>

      <div class="form-group">
        <label>Feature 1 - Descrição</label>
        <input type="text" id="feat1-desc">
      </div>

      <div class="form-group">
        <label>Feature 2 - Título</label>
        <input type="text" id="feat2-title">
      </div>

      <div class="form-group">
        <label>Feature 2 - Descrição</label>
        <input type="text" id="feat2-desc">
      </div>

      <div class="form-group">
        <label>Feature 3 - Título</label>
        <input type="text" id="feat3-title">
      </div>

      <div class="form-group">
        <label>Feature 3 - Descrição</label>
        <input type="text" id="feat3-desc">
      </div>

      <div class="form-group">
        <label>Feature 4 - Título</label>
        <input type="text" id="feat4-title">
      </div>

      <div class="form-group">
        <label>Feature 4 - Descrição</label>
        <input type="text" id="feat4-desc">
      </div>
    </div>

    <div class="card">
      <div class="card-title">Seção CTA</div>

      <div class="form-group">
        <label>Título</label>
        <input type="text" id="cta-title">
      </div>

      <div class="form-group">
        <label>Descrição</label>
        <textarea id="cta-desc" rows="2"></textarea>
      </div>

      <div class="form-group">
        <label>Texto do Botão</label>
        <input type="text" id="cta-btn">
      </div>
    </div>
  </div>

  <div class="section" id="sec-projetos">
    <div class="page-header">
      <h2>Projetos</h2>
      <p>Gerencie os projetos exibidos na landing page</p>
    </div>

    <div style="margin-bottom: 16px;">
      <button class="btn-add" id="btn-novo-projeto">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <path d="M12 5v14M5 12h14"/>
        </svg>
        Novo Projeto
      </button>
    </div>

    <div class="project-list" id="project-list"></div>
  </div>

  <div class="section" id="sec-contato">
    <div class="page-header">
      <h2>Informações de Contato</h2>
      <p>Edite os dados de contato exibidos no site</p>
    </div>

    <div class="card">
      <div class="card-title">Dados de Contato</div>

      <div class="form-group">
        <label>E-mail</label>
        <input type="email" id="contact-email">
      </div>

      <div class="form-group">
        <label>Telefone</label>
        <input type="tel" id="contact-phone">
      </div>
    </div>
  </div>

  <div class="section" id="sec-imagens">
    <div class="page-header">
      <h2>Imagens</h2>
      <p>Atualize as imagens exibidas no site</p>
    </div>

    <div class="card">
      <div class="card-title">Logo</div>

      <div class="upload-zone" id="zone-logo">
        <img id="preview-logo" src="" alt="" style="display:none">
        <p>Clique para enviar a logo PNG, SVG, JPG ou WEBP</p>
        <input type="file" id="input-logo" accept="image/*">
      </div>
    </div>

    <div class="card">
      <div class="card-title">Imagem Hero</div>

      <div class="upload-zone" id="zone-hero">
        <img id="preview-hero" src="" alt="" style="display:none">
        <p>Clique para enviar a imagem principal</p>
        <input type="file" id="input-hero" accept="image/*">
      </div>
    </div>
  </div>
</main>

<div class="modal-overlay" id="modal-projeto">
  <div class="modal">
    <h3 id="modal-title">Novo Projeto</h3>

    <input type="hidden" id="proj-edit-index" value="-1">

    <div class="form-group">
      <label>Título</label>
      <input type="text" id="proj-titulo">
    </div>

    <div class="form-group">
      <label>Descrição</label>
      <textarea id="proj-descricao" rows="2"></textarea>
    </div>

    <div class="form-group">
      <label>URL da Imagem</label>
      <input type="text" id="proj-imagem">
    </div>

    <div class="form-group">
      <label>Link</label>
      <input type="text" id="proj-link">
    </div>

    <div class="modal-actions">
      <button class="btn-cancel" id="btn-cancelar">Cancelar</button>
      <button class="btn-save" id="btn-salvar-projeto">Salvar</button>
    </div>
  </div>
</div>

<div class="toast" id="toast">
  <span class="check">&#10003;</span>
  <span id="toast-msg">Alterações publicadas!</span>
</div>

<script>
  window.IDTNPR_ADMIN_DATA = <?php
    echo json_encode($siteData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  ?>;
</script>
<script src="../assets/js/admin.js"></script>
</body>
</html>