<?php
$tituloPagina = "Login - IDTNPR";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $tituloPagina ?></title>

</head>

<body>

  <header class="header">
    <div class="nav">
      <a href="index.php" class="logo">
        <img src="logo.png" alt="Logo IDTNPR">
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
        <img src="logo.png" alt="Logo IDTNPR">
      </div>

      <h1>Entrar</h1>
      <p>Acesse sua conta para continuar.</p>

      <form class="login-form" action="#" method="POST">
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
          <input 
            type="password" 
            id="senha" 
            name="senha" 
            placeholder="Digite sua senha"
          >
        </div>

        <div class="login-options">
          <label>
            <input type="checkbox" name="lembrar">
            Lembrar-me
          </label>

          <a href="#">Esqueci minha senha</a>
        </div>

        <button type="button" class="btn-primary login-btn">
          Entrar
        </button>
      </form>
    </section>
  </main>

  <script>
    const themeToggle = document.getElementById('theme-toggle');
    const sunIcon = document.getElementById('theme-icon-sun');
    const moonIcon = document.getElementById('theme-icon-moon');

    function setTheme(theme) {
      document.documentElement.setAttribute('data-theme', theme);
      localStorage.setItem('theme', theme);

      if (theme === 'dark') {
        sunIcon.style.display = 'block';
        moonIcon.style.display = 'none';
      } else {
        sunIcon.style.display = 'none';
        moonIcon.style.display = 'block';
      }
    }

    const savedTheme = localStorage.getItem('theme') || 'light';
    setTheme(savedTheme);

    themeToggle.addEventListener('click', function () {
      const currentTheme = document.documentElement.getAttribute('data-theme');
      const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';

      setTheme(nextTheme);
    });

    const menuToggle = document.getElementById('menu-toggle');
    const navContainer = document.getElementById('nav-container');

    menuToggle.addEventListener('click', function () {
      navContainer.classList.toggle('active');
      menuToggle.classList.toggle('active');
    });
  </script>

<style>
    :root {
      --primary: #2563eb;
      --secondary: #10b981;
      --bg: #f8fafc;
      --card: rgba(255, 255, 255, 0.88);
      --text: #0f172a;
      --text-muted: #64748b;
      --border: rgba(148, 163, 184, 0.28);
      --shadow: 0 24px 70px rgba(15, 23, 42, 0.14);
    }

    [data-theme="dark"] {
      --bg: #020617;
      --card: rgba(15, 23, 42, 0.88);
      --text: #f8fafc;
      --text-muted: #94a3b8;
      --border: rgba(148, 163, 184, 0.20);
      --shadow: 0 24px 70px rgba(0, 0, 0, 0.35);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      min-height: 100vh;
      font-family: Arial, Helvetica, sans-serif;
      color: var(--text);
      background:
        radial-gradient(circle at top left, rgba(37, 99, 235, 0.16), transparent 34%),
        radial-gradient(circle at bottom right, rgba(16, 185, 129, 0.16), transparent 34%),
        var(--bg);
    }

    .header {
      width: 100%;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 50;
      background: rgba(255, 255, 255, 0.78);
      border-bottom: 1px solid var(--border);
      backdrop-filter: blur(16px);
    }

    [data-theme="dark"] .header {
      background: rgba(2, 6, 23, 0.78);
    }

    .nav {
      width: min(1180px, calc(100% - 40px));
      height: 78px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 24px;
    }

    .logo {
      display: flex;
      align-items: center;
      text-decoration: none;
      font-size: 1.3rem;
      font-weight: 800;
      color: var(--text);
    }

    .logo img {
      max-height: 52px;
      width: auto;
      display: block;
    }

    .nav-container {
      display: flex;
      align-items: center;
      gap: 26px;
    }

    .nav-container nav {
      display: flex;
      align-items: center;
      gap: 22px;
    }

    .nav-container nav a {
      color: var(--text-muted);
      text-decoration: none;
      font-weight: 700;
      font-size: 0.95rem;
      transition: color 0.2s ease;
    }

    .nav-container nav a:hover {
      color: var(--primary);
    }

    .btn-primary {
      min-height: 44px;
      padding: 0 22px;
      border-radius: 999px;
      border: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      font-weight: 800;
      text-decoration: none;
      cursor: pointer;
      box-shadow: 0 14px 28px rgba(37, 99, 235, 0.22);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 18px 34px rgba(37, 99, 235, 0.28);
    }

    .theme-toggle-btn {
      width: 44px;
      height: 44px;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: transparent;
      color: var(--text);
      display: grid;
      place-items: center;
      cursor: pointer;
    }

    .theme-toggle-btn svg {
      width: 21px;
      height: 21px;
      fill: none;
      stroke: currentColor;
      stroke-width: 2;
    }

    .menu-toggle {
      display: none;
      width: 44px;
      height: 44px;
      border: 1px solid var(--border);
      border-radius: 12px;
      background: transparent;
      cursor: pointer;
    }

    .menu-toggle span {
      display: block;
      width: 22px;
      height: 2px;
      margin: 5px auto;
      background: var(--text);
      transition: 0.2s ease;
    }

    .login-page {
      min-height: 100vh;
      padding: 120px 20px 40px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-box {
      width: 100%;
      max-width: 430px;
      padding: 38px;
      border-radius: 28px;
      background: var(--card);
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
      backdrop-filter: blur(16px);
      text-align: center;
    }

    .login-logo {
      margin-bottom: 20px;
    }

    .login-logo img {
      max-width: 130px;
      height: auto;
    }

    .login-box h1 {
      margin-bottom: 8px;
      font-size: 2rem;
      letter-spacing: -0.03em;
    }

    .login-box p {
      margin-bottom: 28px;
      color: var(--text-muted);
      line-height: 1.5;
    }

    .login-form {
      display: grid;
      gap: 18px;
      text-align: left;
    }

    .input-group {
      display: grid;
      gap: 8px;
    }

    .input-group label {
      font-weight: 700;
      font-size: 0.95rem;
    }

    .input-group input {
      width: 100%;
      height: 52px;
      padding: 0 16px;
      border-radius: 16px;
      border: 1px solid var(--border);
      background: rgba(255, 255, 255, 0.75);
      color: var(--text);
      font-size: 1rem;
      outline: none;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    [data-theme="dark"] .input-group input {
      background: rgba(2, 6, 23, 0.45);
    }

    .input-group input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.14);
    }

    .input-group input::placeholder {
      color: #94a3b8;
    }

    .login-options {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      font-size: 0.9rem;
    }

    .login-options label {
      display: flex;
      align-items: center;
      gap: 7px;
      color: var(--text-muted);
      cursor: pointer;
    }

    .login-options input {
      accent-color: var(--primary);
    }

    .login-options a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 700;
    }

    .login-options a:hover {
      text-decoration: underline;
    }

    .login-btn {
      width: 100%;
      margin-top: 6px;
      font-size: 1rem;
    }

    @media (max-width: 760px) {
      .nav-container {
        position: fixed;
        top: 78px;
        left: 0;
        width: 100%;
        padding: 22px;
        display: none;
        flex-direction: column;
        align-items: stretch;
        background: var(--bg);
        border-bottom: 1px solid var(--border);
      }

      .nav-container.active {
        display: flex;
      }

      .nav-container nav {
        flex-direction: column;
        align-items: stretch;
      }

      .nav-container nav a {
        padding: 10px 0;
      }

      .theme-toggle-btn,
      .nav-container .btn-primary {
        width: 100%;
      }

      .menu-toggle {
        display: block;
      }
    }

    @media (max-width: 520px) {
      .nav {
        width: min(100% - 28px, 1180px);
      }

      .login-box {
        padding: 30px 22px;
      }

      .login-options {
        align-items: flex-start;
        flex-direction: column;
      }
    }
  </style>

</body>
</html>