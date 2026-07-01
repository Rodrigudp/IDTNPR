<?php
// Arquivo convertido para PHP. Mantenha este arquivo na pasta htdocs do XAMPP.
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>IDTNPR - Instituto de Desenvolvimento de Tecnologias do Noroeste Paranaense</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="assets/css/style.css"/>
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
        <a href="#sobre">Sobre</a>
        <a href="quem-somos.php">Quem Somos</a>
        <a href="#contato">Fale conosco</a>
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

<main id="conteudo-principal">
  
  <section class="hero">
    <div class="container hero-grid">
      <div class="hero-text reveal fade-left">
        <h2>
          Seu município pode fazer parte do <span>ecossistema de inovação</span>
        </h2>
        <p>
          Desenvolvemos soluções digitais para modernizar serviços públicos,
          promover transparência e aproximar cidadãos do governo através
          da tecnologia.
        </p>
        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
          <a href="#contato" class="btn-primary">Solicitar Reunião Técnica</a>
        </div>
      </div>

      <div class="hero-image-wrapper reveal fade-right">
        <div class="hero-scanner-container" id="hero-scanner" aria-label="Painel interativo com scanner inteligente da cidade">
          
          <div class="scanner-visual-wrapper">
            <div class="scanner-bg-wrapper">
              <img src="assets/images/cidade.jpg" alt="Mapa e imagem da cidade do Noroeste Paranaense - IDTNPR" id="hero-img-element">
            </div>

            <div class="scanner-laser-bar" aria-hidden="true"></div>

            <div class="scanner-grid-layer" aria-hidden="true"></div>

            <div class="scanner-svg-overlay" aria-hidden="true">
              <svg viewBox="0 0 400 300" fill="none" stroke="currentColor" stroke-width="1">
                <circle cx="200" cy="150" r="110" stroke-dasharray="2,6" opacity="0.3"/>
                <circle cx="200" cy="150" r="70" opacity="0.2"/>
                <path d="M 15 25 L 35 25 L 35 45" opacity="0.7"/>
                <path d="M 385 25 L 365 25 L 365 45" opacity="0.7"/>
                <path d="M 15 275 L 35 275 L 35 255" opacity="0.7"/>
                <path d="M 385 275 L 365 275 L 365 255" opacity="0.7"/>
                <path d="M 100 100 L 150 100 L 170 120 L 230 120 L 250 100 L 300 100" opacity="0.4" stroke-dasharray="4,4"/>
                <path d="M 80 200 L 150 200 L 200 150 M 200 150 L 320 200" opacity="0.4"/>
              </svg>
            </div>
          </div>

          <div class="scanner-hotspots">
            
            <div class="city-hotspot" style="top: 30%; left: 35%;" role="button" aria-haspopup="true" aria-label="Informações sobre Protocolo Digital">
              <div class="hotspot-tooltip">
                <h4>
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="filter:none;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                  Protocolo Digital
                </h4>
                <p>Modernização de processos administrativos públicos com abertura e tramitações 100% digitais, gerando economia e rapidez.</p>
              </div>
            </div>

            <div class="city-hotspot" style="top: 55%; left: 60%;" role="button" aria-haspopup="true" aria-label="Informações sobre Inteligência de Dados">
              <div class="hotspot-tooltip">
                <h4>
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="filter:none;"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                  Inteligência de Dados
                </h4>
                <p>Dashboards dinâmicos e painéis de indicadores em tempo real para embasar decisões eficientes e transparentes dos gestores municipais.</p>
              </div>
            </div>

            <div class="city-hotspot" style="top: 75%; left: 25%;" role="button" aria-haspopup="true" aria-label="Informações sobre Ecossistema de Inovação">
              <div class="hotspot-tooltip">
                <h4>
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="filter:none;"><path d="M9 21h6"/><path d="M9 17h6"/><path d="M12 2v2"/><path d="M12 13V7"/><path d="M21 12h-2"/><path d="M5 12H3"/><circle cx="12" cy="12" r="3"/></svg>
                  Cidade Conectada
                </h4>
                <p>Integração de serviços e canais públicos para aproximar o cidadão da administração municipal através de tecnologia acessível.</p>
              </div>
            </div>

          </div>

        </div>
      </div>

    </div>

    <div class="scroll-indicator" id="scroll-indicator" aria-hidden="true">
      <div class="mouse">
        <div class="wheel"></div>
      </div>
    </div>
  </section>

  <section class="about" id="sobre">
    <div class="container">
      <div class="section-title reveal fade-bottom">
        <span>O que fazemos</span>
        <div class="line"></div>
      </div>

      <div class="about-grid">
        <div class="about-text reveal fade-left">
          <p>
            Atuamos na transformação digital do setor público,
            combinando tecnologia, gestão e conhecimento para gerar
            resultados reais para a sociedade.
          </p>

          <h3 class="about-subtitle">Quem Somos</h3>
          <p class="about-quem-somos">
            Somos uma organização dedicada a aproximar tecnologia e gestão
            pública, apoiando municípios na construção de serviços mais ágeis,
            transparentes e acessíveis à população.
          </p>
          <p class="about-quem-somos">
            Reunimos profissionais de diferentes áreas em torno de um propósito
            comum: transformar a relação entre cidadão e poder público por meio
            da inovação.
          </p>
        </div>

        <div class="features reveal fade-right">
          
          <div class="feature">
            <div class="feature-icon-wrapper" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                <line x1="8" y1="21" x2="16" y2="21"></line>
                <line x1="12" y1="17" x2="12" y2="21"></line>
                <path d="M12 7v5l3 3"></path>
              </svg>
            </div>
            <h3>Transformação Digital Pública</h3>
            <p>Soluções digitais para modernizar serviços e processos públicos.</p>
          </div>

          <div class="feature">
            <div class="feature-icon-wrapper" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
              </svg>
            </div>
            <h3>Dados e Transparência</h3>
            <p>Inteligência de dados para decisões mais eficientes.</p>
          </div>

          <div class="feature">
            <div class="feature-icon-wrapper" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21h6"></path>
                <path d="M9 17h6"></path>
                <path d="M12 2v2"></path>
                <path d="M12 13V7"></path>
                <path d="M21 12h-2"></path>
                <path d="M5 12H3"></path>
                <path d="M16.7 5.3l-1.4 1.4"></path>
                <path d="M8.7 13.3l-1.4 1.4"></path>
                <path d="M16.7 18.7l-1.4-1.4"></path>
                <path d="M8.7 5.3L7.3 6.7"></path>
                <path d="M15 12a3 3 0 1 1-6 0c0-1.7 1.3-3 3-3s3 1.3 3 3z"></path>
              </svg>
            </div>
            <h3>Inovação e Gestão</h3>
            <p>Metodologias que aumentam a eficiência dos órgãos públicos.</p>
          </div>

          <div class="feature">
            <div class="feature-icon-wrapper" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path>
              </svg>
            </div>
            <h3>Capacitação Técnica</h3>
            <p>Formação e transferência de conhecimento para equipes.</p>
          </div>

        </div>
      </div>
    </div>
  </section>

  <section class="projects" id="projetos">
    <div class="container">
      <div class="section-title reveal fade-bottom">
        <span>Projetos em Destaque</span>
        <div class="line"></div>
      </div>

      <div class="project-grid" id="main-project-grid">
        <div class="project-card reveal fade-bottom">
          <div class="project-img-wrapper">
            <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?q=80&w=800&auto=format&fit=crop" alt="Interface de Protocolo Digital" loading="lazy">
          </div>
          <h3>Protocolo Digital</h3>
          <p>Plataforma para abertura, tramitação e acompanhamento de solicitações.</p>
        </div>

        <div class="project-card reveal fade-bottom">
          <div class="project-img-wrapper">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800&auto=format&fit=crop" alt="Equipe de atendimento ao cidadão trabalhando" loading="lazy">
          </div>
          <h3>Atendimento ao Cidadão</h3>
          <p>Centralização de canais de atendimento com mais agilidade.</p>
        </div>

        <div class="project-card reveal fade-bottom">
          <div class="project-img-wrapper">
            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800&auto=format&fit=crop" alt="Gráficos estatísticos em tela" loading="lazy">
          </div>
          <h3>Painel de Indicadores</h3>
          <p>Dashboards inteligentes para acompanhamento da gestão.</p>
        </div>
      </div>
    </div>
  </section>

</main>

<footer class="footer" id="contato">
  <div class="container footer-grid">
    <div class="reveal fade-bottom">
      <div class="footer-logo">
        <img src="assets/images/logo.png" alt="IDTNPR">
      </div>
      <p class="footer-desc">
        Conectamos pessoas, tecnologia e governo para construir soluções
        com impacto social.
      </p>
    </div>

    <div class="reveal fade-bottom" style="transition-delay: 100ms;">
      <h4>Navegação</h4>
      <a href="#sobre">Sobre o Instituto</a>
      <a href="quem-somos.php">Quem Somos</a>
      <a href="solucoes.php">Soluções</a>
      <a href="noticias.php">Notícias</a>
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
      <p class="contact-info" id="footer-email">
        contato@idtnpr.org.br
      </p>
      <p class="contact-info" id="footer-phone">
        (44) 99999-9999
      </p>
    </div>
  </div>

  <div class="footer-bottom">
    <p>&copy; 2026 IDTNPR. Todos os direitos reservados. Projetado com foco em IHC.</p>
  </div>
</footer>

<button id="back-to-top" aria-label="Voltar para o topo da página" title="Voltar ao Topo">
  <svg viewBox="0 0 24 24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
    <line x1="12" y1="19" x2="12" y2="5"></line>
    <polyline points="5 12 12 5 19 12"></polyline>
  </svg>
</button>

<script src="assets/js/api.js"></script>
<script src="assets/js/index.js"></script>

</body>
</html>