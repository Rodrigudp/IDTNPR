<?php
// Arquivo convertido para PHP. Mantenha este arquivo na pasta htdocs do XAMPP.
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Quem Somos - IDTNPR</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css"/>
  <link rel="stylesheet" href="assets/css/pages.css"/>
</head>
<body>
<a href="#conteudo-principal" class="skip-link">Ir para o conteúdo principal</a>

<header class="header">
  <div class="nav">
    <a href="index.php" class="logo" aria-label="Página inicial do IDTNPR">
      <img src="assets/images/logo.png" alt="Logo IDTNPR" id="site-logo">
    </a>
    <div class="nav-container" id="nav-container">
      <nav aria-label="Menu principal">
        <a href="index.php#sobre">Sobre</a>
        <a href="quem-somos.php">Quem Somos</a>
        <a href="index.php#contato">Fale conosco</a>
        <a href="noticias.php">Notícias</a>
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
      <span class="current">Quem Somos</span>
    </nav>
  </div>
</div>

<main id="conteudo-principal">

  <section class="page-section page-hero">
    <div class="container">
      <div class="section-header reveal fade-bottom">
        <span class="label">Institucional</span>
        <h1>Quem Somos</h1>
        <p class="page-subtitle">Somos o Instituto de Desenvolvimento e Transformação Digital do Noroeste do Paraná, dedicados a modernizar a gestão pública municipal por meio de tecnologia, capacitação e inovação colaborativa.</p>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-header reveal fade-bottom">
        <span class="label">Trajetória</span>
        <h2>Nossa História</h2>
      </div>
      <div class="qs-timeline">
        <div class="timeline-item reveal fade-bottom">
          <div class="timeline-year">2020</div>
          <div class="timeline-content">
            <h3>Fundação do Instituto</h3>
            <p>Criado a partir da união de profissionais de tecnologia e gestores públicos da região Noroeste do Paraná, com o objetivo de acelerar a transformação digital nos municípios.</p>
          </div>
        </div>
        <div class="timeline-item reveal fade-bottom">
          <div class="timeline-year">2021</div>
          <div class="timeline-content">
            <h3>Primeiros Projetos Piloto</h3>
            <p>Implantação do sistema de protocolo digital em 3 municípios parceiros, eliminando processos em papel e reduzindo o tempo de tramitação em até 60%.</p>
          </div>
        </div>
        <div class="timeline-item reveal fade-bottom">
          <div class="timeline-year">2022</div>
          <div class="timeline-content">
            <h3>Expansão Regional</h3>
            <p>Ampliação para 8 municípios com a inclusão de módulos de transparência, ouvidoria digital e capacitação de servidores públicos em letramento digital.</p>
          </div>
        </div>
        <div class="timeline-item reveal fade-bottom">
          <div class="timeline-year">2023</div>
          <div class="timeline-content">
            <h3>Reconhecimento Nacional</h3>
            <p>Premiação no Prêmio Boas Práticas em Governo Digital. Lançamento da plataforma de dados abertos regionais e início do programa de mentoria para startups govtech.</p>
          </div>
        </div>
        <div class="timeline-item reveal fade-bottom">
          <div class="timeline-year">2025</div>
          <div class="timeline-content">
            <h3>Consolidação e Novas Fronteiras</h3>
            <p>Presença em mais de 12 municípios, com mais de 500 servidores capacitados. Início do projeto de inteligência artificial aplicada à gestão pública e integração interoperável entre sistemas municipais.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-header reveal fade-bottom">
        <span class="label">Propósito</span>
        <h2>Missão, Visão e Valores</h2>
      </div>
      <div class="qs-mvv-grid">
        <div class="mvv-card reveal fade-bottom">
          <div class="mvv-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="7"></circle>
              <line x1="12" y1="9.5" x2="12" y2="14.5"></line>
              <line x1="9.5" y1="12" x2="14.5" y2="12"></line>
            </svg>
          </div>
          <h3>Missão</h3>
          <p>Promover a transformação digital da gestão pública municipal no Noroeste do Paraná, entregando soluções tecnológicas acessíveis, capacitando servidores e fortalecendo a cidadania digital.</p>
        </div>

        <div class="mvv-card reveal fade-bottom">
          <div class="mvv-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 12s3-5.5 8-5.5 8 5.5 8 5.5-3 5.5-8 5.5S4 12 4 12z"></path>
              <circle cx="12" cy="12" r="2"></circle>
            </svg>
          </div>
          <h3>Visão</h3>
          <p>Ser referência nacional em inovação pública regional, tornando o Noroeste do Paraná um polo de excelência em governo digital, com municípios conectados, transparentes e centrados no cidadão.</p>
        </div>

        <div class="mvv-card reveal fade-bottom">
          <div class="mvv-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polygon points="12 5 14.16 9.38 19 10.09 15.5 13.5 16.33 18.31 12 16.04 7.67 18.31 8.5 13.5 5 10.09 9.84 9.38 12 5"></polygon>
            </svg>
          </div>
          <h3>Valores</h3>
          <p>Transparência, colaboração, inclusão digital, inovação com responsabilidade social, compromisso com o interesse público e respeito à diversidade regional.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-header reveal fade-bottom">
        <span class="label">Impacto</span>
        <h2>Nossos Números</h2>
      </div>
      <div class="qs-stats-grid">
        <div class="stat-card reveal fade-bottom">
          <span class="number" data-target="12">0</span>
          <span class="stat-suffix">+</span>
          <p>Municípios atendidos</p>
        </div>
        <div class="stat-card reveal fade-bottom">
          <span class="number" data-target="30">0</span>
          <span class="stat-suffix">+</span>
          <p>Projetos realizados</p>
        </div>
        <div class="stat-card reveal fade-bottom">
          <span class="number" data-target="500">0</span>
          <span class="stat-suffix">+</span>
          <p>Servidores capacitados</p>
        </div>
        <div class="stat-card reveal fade-bottom">
          <span class="number" data-target="10000">0</span>
          <span class="stat-suffix">+</span>
          <p>Protocolos digitais</p>
        </div>
      </div>
    </div>
  </section>

</main>

<footer class="footer">
  <div class="container footer-grid">
    <div class="reveal fade-bottom">
      <div class="footer-logo"><img src="assets/images/logo.png" alt="IDTNPR"></div>
      <p class="footer-desc">Conectamos pessoas, tecnologia e governo para construir soluções com impacto social.</p>
    </div>
    <div class="reveal fade-bottom" style="transition-delay: 100ms;">
      <h4>Navegação</h4>
      <a href="index.php#sobre">Sobre o Instituto</a>
      <a href="quem-somos.php">Quem Somos</a>
      <a href="solucoes.php">Soluções</a>
      <a href="noticias.php">Notícias</a>
      <a href="index.php#contato">Contato</a>
    </div>
    <div class="reveal fade-bottom" style="transition-delay: 200ms;">
      <h4>Institucional</h4>
      <a href="quem-somos.php">Equipe</a>
      <a href="#">Governança</a>
      <a href="#">Transparência</a>
      <a href="#">Trabalhe conosco</a>
    </div>
    <div class="reveal fade-bottom" style="transition-delay: 300ms;">
      <h4>Contato</h4>
      <p class="contact-info">contato@idtnpr.org.br</p>
      <p class="contact-info">(44) 99999-9999</p>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; 2026 IDTNPR. Todos os direitos reservados. Projetado com foco em IHC.</p>
  </div>
</footer>

<button id="back-to-top" aria-label="Voltar ao topo">↑</button>

<script src="assets/js/quem-somos.js"></script>
</body>
</html>