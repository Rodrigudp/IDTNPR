const themeToggle = document.getElementById('theme-toggle');
const sunIcon = document.getElementById('theme-icon-sun');
const moonIcon = document.getElementById('theme-icon-moon');
const tabs = document.querySelectorAll('[data-auth-tab]');
const panels = document.querySelectorAll('[data-auth-panel]');
const subtitle = document.getElementById('auth-subtitle');
const feedback = document.getElementById('auth-feedback');
const loginButton = document.querySelector('.login-btn');
const registerButton = document.querySelector('.register-btn');
const rememberInput = document.getElementById('lembrar');
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('senha');

const REMEMBER_KEY = 'idtnpr_remembered_email';
const REGISTERED_USER_KEY = 'idtnpr_registered_user';
const SESSION_KEY = 'idtnpr_admin_logged_in';

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

function showFeedback(message, type) {
  feedback.textContent = message || '';
  feedback.classList.remove('error', 'success');

  if (type) {
    feedback.classList.add(type);
  }
}

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function switchTab(tabName) {
  tabs.forEach(function(tab) {
    const isActive = tab.getAttribute('data-auth-tab') === tabName;
    tab.classList.toggle('active', isActive);
    tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
  });

  panels.forEach(function(panel) {
    const isActive = panel.getAttribute('data-auth-panel') === tabName;
    panel.classList.toggle('active', isActive);
    panel.hidden = !isActive;
  });

  subtitle.textContent = tabName === 'login'
    ? 'Acesse sua conta para continuar.'
    : 'Crie seu acesso para entrar no painel.';

  showFeedback('', '');
}

function loadRememberedEmail() {
  const rememberedEmail = localStorage.getItem(REMEMBER_KEY);

  if (rememberedEmail) {
    emailInput.value = rememberedEmail;
    rememberInput.checked = true;
  }
}

function updateRememberedEmail(email) {
  if (rememberInput.checked) {
    localStorage.setItem(REMEMBER_KEY, email);
    return;
  }

  localStorage.removeItem(REMEMBER_KEY);
}

function setupPasswordToggles() {
  document.querySelectorAll('[data-password-toggle]').forEach(function(button) {
    button.addEventListener('click', function() {
      const input = document.getElementById(button.getAttribute('data-password-toggle'));

      if (!input) {
        return;
      }

      const shouldShow = input.type === 'password';
      input.type = shouldShow ? 'text' : 'password';
      button.classList.toggle('is-visible', shouldShow);
      button.setAttribute('aria-label', shouldShow ? 'Ocultar senha' : 'Mostrar senha');
      button.setAttribute('title', shouldShow ? 'Ocultar senha' : 'Mostrar senha');
    });
  });
}

async function handleLogin() {
  const email = emailInput.value.trim();
  const password = passwordInput.value.trim();

  if (!email || !password) {
    showFeedback('Preencha e-mail e senha para entrar.', 'error');
    return;
  }

  if (!isValidEmail(email)) {
    showFeedback('Digite um e-mail válido.', 'error');
    return;
  }

  loginButton.disabled = true;
  showFeedback('Entrando...', '');

  try {
    const resposta = await API.post('/auth/login', {
      corpo: { email: email, senha: password }
    });

    API.salvarToken(resposta.accessToken);
    updateRememberedEmail(email);
    sessionStorage.setItem(SESSION_KEY, 'true');
    window.location.href = 'admin/admin.php';
  } catch (erro) {
    if (erro.status === 429) {
      showFeedback('Muitas tentativas de login. Aguarde alguns minutos e tente novamente.', 'error');
    } else if (erro.status === 401) {
      showFeedback('E-mail ou senha incorretos.', 'error');
    } else {
      showFeedback(erro.message || 'Não foi possível entrar. Verifique se a API está no ar.', 'error');
    }
    loginButton.disabled = false;
  }
}

function handleRegister() {
  const name = document.getElementById('cadastro-nome').value.trim();
  const email = document.getElementById('cadastro-email').value.trim();
  const password = document.getElementById('cadastro-senha').value.trim();
  const confirmPassword = document.getElementById('cadastro-confirma-senha').value.trim();

  if (!name || !email || !password || !confirmPassword) {
    showFeedback('Preencha todos os campos do cadastro.', 'error');
    return;
  }

  if (!isValidEmail(email)) {
    showFeedback('Digite um e-mail válido para cadastrar.', 'error');
    return;
  }

  if (password.length < 6) {
    showFeedback('A senha precisa ter pelo menos 6 caracteres.', 'error');
    return;
  }

  if (password !== confirmPassword) {
    showFeedback('As senhas não conferem.', 'error');
    return;
  }

  localStorage.setItem(REGISTERED_USER_KEY, JSON.stringify({
    name: name,
    email: email
  }));

  emailInput.value = email;
  passwordInput.value = '';
  switchTab('login');
  showFeedback('Cadastro criado. Agora informe sua senha para entrar.', 'success');
}

const savedTheme = localStorage.getItem('theme') || 'light';
setTheme(savedTheme);
loadRememberedEmail();
setupPasswordToggles();

themeToggle.addEventListener('click', function() {
  const currentTheme = document.documentElement.getAttribute('data-theme');
  const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';

  setTheme(nextTheme);
});

tabs.forEach(function(tab) {
  tab.addEventListener('click', function() {
    switchTab(tab.getAttribute('data-auth-tab'));
  });
});

loginButton.addEventListener('click', handleLogin);
registerButton.addEventListener('click', handleRegister);

document.getElementById('login-form').addEventListener('submit', function(event) {
  event.preventDefault();
  handleLogin();
});

document.getElementById('register-form').addEventListener('submit', function(event) {
  event.preventDefault();
  handleRegister();
});

const menuToggle = document.getElementById('menu-toggle');
const navContainer = document.getElementById('nav-container');

if (menuToggle && navContainer) {
  menuToggle.addEventListener('click', function() {
    navContainer.classList.toggle('active');
    menuToggle.classList.toggle('active');
  });
}
