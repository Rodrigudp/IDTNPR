<?php
$tituloPagina = "Login - IDTNPR";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $tituloPagina ?></title>

  <link rel="stylesheet" href="assets/css/login.css"/>
</head>

<body>

  <header class="header">
    <div class="nav">
      <a href="index.php" class="logo">
        <img src="assets/images/logo.png" alt="Logo IDTNPR">
      </a>
        <button class="theme-toggle-btn" id="theme-toggle" type="button" aria-label="Alternar tema">
          <svg id="theme-icon-sun" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
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

          <svg id="theme-icon-moon" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
          </svg>
        </button>
      </div>
    </div>
  </header>

  <main class="login-page">
    <section class="login-box">
      <div class="login-logo">
        <img src="assets/images/logo.png" alt="Logo IDTNPR">
      </div>

      <h1>Entrar</h1>
      <p id="auth-subtitle">Acesse sua conta para continuar.</p>

      <div class="auth-tabs" role="tablist" aria-label="Acesso">
        <button class="auth-tab active" type="button" id="tab-login" data-auth-tab="login" role="tab" aria-selected="true" aria-controls="login-form">Entrar</button>
        <button class="auth-tab" type="button" id="tab-register" data-auth-tab="register" role="tab" aria-selected="false" aria-controls="register-form">Cadastrar-se</button>
      </div>

      <form class="login-form auth-form active" id="login-form" action="#" method="POST" data-auth-panel="login">
        <div class="input-group">
          <label for="email">E-mail</label>
          <input 
            type="email" 
            id="email" 
            name="email" 
            placeholder="Digite seu e-mail"
          >
        </div>

        <div class="input-group">
          <label for="senha">Senha</label>
          <div class="password-field">
            <input
              type="password"
              id="senha"
              name="senha"
              placeholder="Digite sua senha"
            >
            <button type="button" class="password-toggle" data-password-toggle="senha" aria-label="Mostrar senha" title="Mostrar senha">
              <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
              <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M3 3l18 18"></path>
                <path d="M10.6 10.6A2 2 0 0 0 12 14a2 2 0 0 0 1.4-.6"></path>
                <path d="M8.1 5.5A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a17.7 17.7 0 0 1-2.2 3.1"></path>
                <path d="M6.6 6.6C3.6 8.6 2 12 2 12s3.5 7 10 7a10.8 10.8 0 0 0 5.4-1.5"></path>
              </svg>
            </button>
          </div>
        </div>

        <div class="login-options">
          <label>
            <input type="checkbox" id="lembrar" name="lembrar">
            Lembrar-me
          </label>

          <a href="#">Esqueci minha senha</a>
        </div>

        <button type="button" class="btn-primary login-btn">
          Entrar
        </button>
      </form>

      <form class="login-form auth-form" id="register-form" action="#" method="POST" data-auth-panel="register" hidden>
        <div class="input-group">
          <label for="cadastro-nome">Nome completo</label>
          <input
            type="text"
            id="cadastro-nome"
            name="cadastro-nome"
            placeholder="Digite seu nome"
          >
        </div>

        <div class="input-group">
          <label for="cadastro-email">E-mail</label>
          <input
            type="email"
            id="cadastro-email"
            name="cadastro-email"
            placeholder="Digite seu e-mail"
          >
        </div>

        <div class="input-group">
          <label for="cadastro-senha">Senha</label>
          <div class="password-field">
            <input
              type="password"
              id="cadastro-senha"
              name="cadastro-senha"
              placeholder="Crie uma senha"
            >
            <button type="button" class="password-toggle" data-password-toggle="cadastro-senha" aria-label="Mostrar senha" title="Mostrar senha">
              <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
              <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M3 3l18 18"></path>
                <path d="M10.6 10.6A2 2 0 0 0 12 14a2 2 0 0 0 1.4-.6"></path>
                <path d="M8.1 5.5A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a17.7 17.7 0 0 1-2.2 3.1"></path>
                <path d="M6.6 6.6C3.6 8.6 2 12 2 12s3.5 7 10 7a10.8 10.8 0 0 0 5.4-1.5"></path>
              </svg>
            </button>
          </div>
        </div>

        <div class="input-group">
          <label for="cadastro-confirma-senha">Confirmar senha</label>
          <div class="password-field">
            <input
              type="password"
              id="cadastro-confirma-senha"
              name="cadastro-confirma-senha"
              placeholder="Repita a senha"
            >
            <button type="button" class="password-toggle" data-password-toggle="cadastro-confirma-senha" aria-label="Mostrar senha" title="Mostrar senha">
              <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
              <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M3 3l18 18"></path>
                <path d="M10.6 10.6A2 2 0 0 0 12 14a2 2 0 0 0 1.4-.6"></path>
                <path d="M8.1 5.5A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a17.7 17.7 0 0 1-2.2 3.1"></path>
                <path d="M6.6 6.6C3.6 8.6 2 12 2 12s3.5 7 10 7a10.8 10.8 0 0 0 5.4-1.5"></path>
              </svg>
            </button>
          </div>
        </div>

        <button type="button" class="btn-primary register-btn">
          Cadastrar-se
        </button>
      </form>

      <p class="auth-feedback" id="auth-feedback" role="status" aria-live="polite"></p>
    </section>
  </main>
  <script src="assets/js/login.js"></script>

</body>
</html>
