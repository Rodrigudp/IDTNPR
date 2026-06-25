<?php
declare(strict_types=1);

/**
 * Página: Soluções - IDTNPR
 *
 * Para alterar conteúdo futuramente, edite principalmente os arrays abaixo:
 * - $menuItems
 * - $solutions
 * - $steps
 * - $municipalities
 * - $footerColumns
 * - $contactInfo
 */

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$page = [
    'title' => 'Soluções - IDTNPR',
    'logo' => 'logo.png',
    'hero_label' => 'Transformação Digital',
    'hero_title' => 'Nossas Soluções',
    'hero_subtitle' => 'Desenvolvemos ferramentas e plataformas que aproximam a gestão pública dos cidadãos, promovendo transparência, eficiência e inclusão digital nos municípios do Noroeste do Paraná.',
];

$menuItems = [
    ['label' => 'Sobre', 'href' => 'index.php#sobre'],
    ['label' => 'Quem Somos', 'href' => 'quem-somos.php'],
    ['label' => 'Fale conosco', 'href' => 'index.php#contato'],
    ['label' => 'Notícias', 'href' => 'noticias.php'],
];

$solutionsSection = [
    'label' => 'O que oferecemos',
    'title' => 'Soluções para Municípios',
];

$solutions = [
    [
        'active' => true,
        'title' => 'Protocolo Digital',
        'description' => 'Sistema de abertura e acompanhamento de solicitações cidadãs, com rastreamento em tempo real e notificações automáticas.',
        'href' => 'protocolo.php',
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>',
    ],
    [
        'active' => false,
        'title' => 'Dashboard de Transparência',
        'description' => 'Painel com indicadores e métricas municipais em tempo real, acessível a gestores e cidadãos para acompanhamento de políticas públicas.',
        'href' => '#',
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>',
    ],
    [
        'active' => false,
        'title' => 'Capacitação Técnica',
        'description' => 'Treinamentos para servidores públicos em tecnologia e gestão, promovendo autonomia e competência digital nas prefeituras.',
        'href' => '#',
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>',
    ],
    [
        'active' => false,
        'title' => 'Gestão Inteligente',
        'description' => 'Ferramentas de gestão baseadas em dados para tomada de decisão, com relatórios automatizados e alertas estratégicos.',
        'href' => '#',
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>',
    ],
    [
        'active' => false,
        'title' => 'Portal do Cidadão',
        'description' => 'Interface unificada para acesso a serviços municipais, simplificando a relação entre prefeitura e população.',
        'href' => '#',
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
    ],
    [
        'active' => false,
        'title' => 'Integração de Dados',
        'description' => 'Conectividade entre sistemas municipais para eficiência operacional, eliminando retrabalho e inconsistências.',
        'href' => '#',
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>',
    ],
];

$methodologySection = [
    'label' => 'Metodologia',
    'title' => 'Como Funciona',
];

$steps = [
    [
        'number' => '1',
        'title' => 'Diagnóstico',
        'description' => 'Análise das necessidades e infraestrutura do município, identificando oportunidades de melhoria e prioridades de atuação.',
    ],
    [
        'number' => '2',
        'title' => 'Implementação',
        'description' => 'Desenvolvimento e implantação das soluções personalizadas, com acompanhamento técnico e capacitação da equipe local.',
    ],
    [
        'number' => '3',
        'title' => 'Acompanhamento',
        'description' => 'Suporte contínuo e evolução das ferramentas, garantindo que as soluções se adaptem às demandas do município.',
    ],
];

$municipalitiesSection = [
    'label' => 'Abrangência',
    'title' => 'Municípios Atendidos',
];

$municipalities = [
    'Umuarama',
    'Cianorte',
    'Paranavaí',
    'Campo Mourão',
    'Cruzeiro do Oeste',
    'Maringá',
    'Londrina',
    'Cascavel',
    'Toledo',
    'Guaíra',
    'Iporã',
    'Altônia',
];

$footerDescription = 'Conectamos pessoas, tecnologia e governo para construir soluções com impacto social.';

$footerColumns = [
    [
        'title' => 'Navegação',
        'delay' => '100ms',
        'links' => [
            ['label' => 'Sobre o Instituto', 'href' => 'index.php#sobre'],
            ['label' => 'Quem Somos', 'href' => 'quem-somos.php'],
            ['label' => 'Soluções', 'href' => 'solucoes.php'],
            ['label' => 'Notícias', 'href' => 'noticias.php'],
            ['label' => 'Contato', 'href' => 'index.php#contato'],
        ],
    ],
    [
        'title' => 'Institucional',
        'delay' => '200ms',
        'links' => [
            ['label' => 'Equipe', 'href' => 'quem-somos.php'],
            ['label' => 'Governança', 'href' => '#'],
            ['label' => 'Transparência', 'href' => '#'],
            ['label' => 'Trabalhe conosco', 'href' => '#'],
        ],
    ],
];

$contactInfo = [
    'email' => 'contato@idtnpr.org.br',
    'phone' => '(44) 99999-9999',
];

$activeSolutions = array_values(array_filter(
    $solutions,
    static fn (array $solution): bool => $solution['active'] === true
));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= e($page['title']) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css"/>
  <link rel="stylesheet" href="pages.css"/>
</head>
<body>
<a href="#conteudo-principal" class="skip-link">Ir para o conteúdo principal</a>

<header class="header">
  <div class="nav">
    <a href="index.php" class="logo" aria-label="Página inicial do IDTNPR">
      <img src="<?= e($page['logo']) ?>" alt="Logo IDTNPR" id="site-logo">
    </a>
    <div class="nav-container" id="nav-container">
      <nav aria-label="Menu principal">
        <?php foreach ($menuItems as $item): ?>
          <a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a>
        <?php endforeach; ?>
      </nav>
      <a href="solucoes.php" class="btn-primary">Soluções</a>
      <button class="theme-toggle-btn" id="theme-toggle" aria-label="Alternar tema claro/escuro" title="Alternar tema">
        <svg id="theme-icon-sun" class="sun" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="5"></circle>
          <line x1="12" y1="1" x2="12" y2="3"></line>
          <line x1="12" y1="21" x2="12" y2="23"></line>
          <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
          <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
          <line x1="1" y1="12" x2="3" y2="12"></line>
          <line x1="21" y1="12" x2="23" y2="12"></line>
          <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
          <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
        </svg>
        <svg id="theme-icon-moon" class="moon" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
          <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
        </svg>
      </button>
    </div>
    <button class="menu-toggle" id="menu-toggle" aria-label="Abrir menu de navegação" aria-expanded="false">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>
</header>

<div class="breadcrumb-bar">
  <div class="container">
    <nav class="breadcrumb-nav" aria-label="Breadcrumb">
      <a href="index.php">Início</a>
      <span class="sep">/</span>
      <span class="current">Soluções</span>
    </nav>
  </div>
</div>

<main id="conteudo-principal">

  <section class="page-section page-hero">
    <div class="container">
      <div class="section-header reveal fade-bottom">
        <span class="label"><?= e($page['hero_label']) ?></span>
        <h1><?= e($page['hero_title']) ?></h1>
        <p class="subtitle"><?= e($page['hero_subtitle']) ?></p>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-header reveal fade-bottom">
        <span class="label"><?= e($solutionsSection['label']) ?></span>
        <h2><?= e($solutionsSection['title']) ?></h2>
      </div>
      <div class="sol-grid">
        <?php foreach ($activeSolutions as $solution): ?>
          <div class="sol-card reveal fade-bottom">
            <div class="icon">
              <?= $solution['icon'] ?>
            </div>
            <h3><?= e($solution['title']) ?></h3>
            <p><?= e($solution['description']) ?></p>
            <a href="<?= e($solution['href']) ?>" class="btn-link">Saiba mais →</a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-header reveal fade-bottom">
        <span class="label"><?= e($methodologySection['label']) ?></span>
        <h2><?= e($methodologySection['title']) ?></h2>
      </div>
      <div class="sol-steps">
        <?php foreach ($steps as $step): ?>
          <div class="sol-step reveal fade-bottom">
            <div class="step-number"><?= e($step['number']) ?></div>
            <h3><?= e($step['title']) ?></h3>
            <p><?= e($step['description']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-header reveal fade-bottom">
        <span class="label"><?= e($municipalitiesSection['label']) ?></span>
        <h2><?= e($municipalitiesSection['title']) ?></h2>
      </div>
      <div class="sol-municipios reveal fade-bottom">
        <?php foreach ($municipalities as $municipality): ?>
          <span class="tag"><?= e($municipality) ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

<footer class="footer">
  <div class="container footer-grid">
    <div class="reveal fade-bottom">
      <div class="footer-logo"><img src="<?= e($page['logo']) ?>" alt="IDTNPR"></div>
      <p class="footer-desc"><?= e($footerDescription) ?></p>
    </div>

    <?php foreach ($footerColumns as $column): ?>
      <div class="reveal fade-bottom" style="transition-delay: <?= e($column['delay']) ?>;">
        <h4><?= e($column['title']) ?></h4>
        <?php foreach ($column['links'] as $link): ?>
          <a href="<?= e($link['href']) ?>"><?= e($link['label']) ?></a>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>

    <div class="reveal fade-bottom" style="transition-delay: 300ms;">
      <h4>Contato</h4>
      <p class="contact-info"><?= e($contactInfo['email']) ?></p>
      <p class="contact-info"><?= e($contactInfo['phone']) ?></p>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; 2026 IDTNPR. Todos os direitos reservados. Projetado com foco em IHC.</p>
  </div>
</footer>

<button id="back-to-top" aria-label="Voltar ao topo">↑</button>

<script>
(function() {
  // Theme toggle
  function getPreferredTheme() {
    const stored = localStorage.getItem('theme');
    if (stored) return stored;
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    const sun = document.getElementById('theme-icon-sun');
    const moon = document.getElementById('theme-icon-moon');

    if (theme === 'dark') {
      sun.style.display = 'none';
      moon.style.display = 'block';
    } else {
      sun.style.display = 'block';
      moon.style.display = 'none';
    }
  }

  setTheme(getPreferredTheme());

  document.getElementById('theme-toggle').addEventListener('click', function() {
    const current = document.documentElement.getAttribute('data-theme');
    setTheme(current === 'dark' ? 'light' : 'dark');
  });

  // Mobile menu toggle
  const menuToggle = document.getElementById('menu-toggle');
  const navContainer = document.getElementById('nav-container');

  menuToggle.addEventListener('click', function() {
    const expanded = this.getAttribute('aria-expanded') === 'true';
    this.setAttribute('aria-expanded', !expanded);
    navContainer.classList.toggle('active');
    this.classList.toggle('active');
  });

  // Header scroll effect + back to top
  const header = document.querySelector('.header');
  const backToTop = document.getElementById('back-to-top');

  window.addEventListener('scroll', function() {
    if (window.scrollY > 50) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }

    if (window.scrollY > 300) {
      backToTop.classList.add('visible');
    } else {
      backToTop.classList.remove('visible');
    }
  });

  backToTop.addEventListener('click', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  // Scroll reveal with IntersectionObserver
  const reveals = document.querySelectorAll('.reveal');

  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('active');
      }
    });
  }, { threshold: 0.1 });

  reveals.forEach(function(el) {
    observer.observe(el);
  });
})();
</script>
</body>
</html>