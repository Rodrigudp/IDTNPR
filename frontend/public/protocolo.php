<?php
// Arquivo convertido para PHP. Mantenha este arquivo na pasta htdocs do XAMPP.
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Protocolo Digital - IDTNPR</title>
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
      <a href="solucoes.php">Soluções</a>
      <span class="sep">/</span>
      <span class="current">Protocolo Digital</span>
    </nav>
  </div>
</div>

<main id="conteudo-principal">

  <section class="page-hero">
    <div class="container">
      <h1 class="reveal fade-bottom">Protocolo Digital</h1>
      <p class="reveal fade-bottom">Solução completa para a gestão eletrônica de documentos e processos, com agilidade, segurança e rastreabilidade.</p>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="proto-layout">

        <div class="proto-form-col reveal fade-center">
          <div class="proto-form-card">
            <h2>Abrir nova solicitação</h2>

            <div class="proto-form-row">
              <div class="proto-form-group">
                <label for="proto-nome">Nome completo</label>
                <input type="text" id="proto-nome" placeholder="Digite seu nome completo" />
              </div>
              <div class="proto-form-group">
                <label for="proto-cpf">CPF</label>
                <input type="text" id="proto-cpf" placeholder="000.000.000-00" />
              </div>
            </div>

            <div class="proto-form-row">
              <div class="proto-form-group">
                <label for="proto-email">E-mail</label>
                <input type="email" id="proto-email" placeholder="Digite seu e-mail" />
              </div>
              <div class="proto-form-group">
                <label for="proto-tel">Telefone</label>
                <input type="text" id="proto-tel" placeholder="(00) 00000-0000" />
              </div>
            </div>

            <div class="proto-form-row single">
              <div class="proto-form-group">
                <label for="proto-tipo">Tipo de solicitação</label>
                <select id="proto-tipo">
                  <option value="" disabled selected>Selecione uma opção</option>
                  <option value="reclamacao">Reclamação</option>
                  <option value="solicitacao">Solicitação de Serviço</option>
                  <option value="informacao">Pedido de Informação</option>
                  <option value="sugestao">Sugestão</option>
                </select>
              </div>
            </div>

            <div class="proto-form-row single">
              <div class="proto-form-group">
                <label for="proto-desc">Descrição da solicitação</label>
                <textarea id="proto-desc" placeholder="Descreva sua solicitação com o máximo de detalhes possíveis..." rows="5"></textarea>
              </div>
            </div>

            <div class="proto-form-row single">
              <div class="proto-form-group">
                <label>Anexos opcional</label>
                <div class="proto-dropzone" onclick="document.getElementById('file-input').click()">
                  <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                  </svg>
                  <p>Arraste e solte arquivos aqui ou clique para selecionar</p>
                  <span class="hint">Formatos aceitos: PDF, JPG, PNG. Tamanho máximo: 10MB</span>
                  <input id="file-input" type="file" multiple accept=".pdf,.jpg,.png" style="display:none">
                </div>
              </div>
            </div>

            <button class="proto-btn-submit">Enviar solicitação</button>
          </div>
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

<script src="assets/js/protocolo.js"></script>

</body>
</html>