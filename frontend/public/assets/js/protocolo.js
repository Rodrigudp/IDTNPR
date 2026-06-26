(function() {
  const themeToggle = document.getElementById('theme-toggle');
  const sunIcon = document.getElementById('theme-icon-sun');
  const moonIcon = document.getElementById('theme-icon-moon');

  function getPreferredTheme() {
    const saved = localStorage.getItem('theme');
    if (saved) return saved;
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

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

  setTheme(getPreferredTheme());
  themeToggle.addEventListener('click', () => {
    const current = document.documentElement.getAttribute('data-theme');
    setTheme(current === 'dark' ? 'light' : 'dark');
  });

  const menuToggle = document.getElementById('menu-toggle');
  const navContainer = document.getElementById('nav-container');
  const navLinks = navContainer.querySelectorAll('nav a, .btn-primary');

  function toggleMenu() {
    const isActive = navContainer.classList.toggle('active');
    menuToggle.classList.toggle('active');
    menuToggle.setAttribute('aria-expanded', isActive ? 'true' : 'false');
  }

  menuToggle.addEventListener('click', toggleMenu);
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      if (navContainer.classList.contains('active')) toggleMenu();
    });
  });

  const header = document.querySelector('.header');
  const backToTopBtn = document.getElementById('back-to-top');

  window.addEventListener('scroll', () => {
    header.classList.toggle('scrolled', window.scrollY > 40);
    backToTopBtn.classList.toggle('show', window.scrollY > 500);
  });

  backToTopBtn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (!prefersReducedMotion) {
    const revealElements = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('active');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    revealElements.forEach(el => observer.observe(el));
  } else {
    document.querySelectorAll('.reveal').forEach(el => el.classList.add('active'));
  }

  const submitBtn = document.getElementById('proto-submit');
  const feedback = document.getElementById('proto-feedback');
  const fileInput = document.getElementById('file-input');

  function mostrarFeedback(mensagem, tipo) {
    if (!feedback) return;
    feedback.textContent = mensagem || '';
    feedback.classList.remove('error', 'success');
    if (tipo) feedback.classList.add(tipo);
  }

  function valor(id) {
    const el = document.getElementById(id);
    return el ? el.value.trim() : '';
  }

  async function enviarAnexos(numero) {
    if (!fileInput || !fileInput.files || fileInput.files.length === 0) return;

    for (let i = 0; i < fileInput.files.length; i++) {
      const formData = new FormData();
      formData.append('arquivo', fileInput.files[i]);
      await API.post('/protocolos/' + encodeURIComponent(numero) + '/anexos', { formData: formData });
    }
  }

  function limparFormulario() {
    ['proto-nome', 'proto-cpf', 'proto-email', 'proto-tel', 'proto-desc'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.value = '';
    });
    const tipo = document.getElementById('proto-tipo');
    if (tipo) tipo.selectedIndex = 0;
    if (fileInput) fileInput.value = '';
  }

  async function enviarSolicitacao() {
    const corpo = {
      nome: valor('proto-nome'),
      cpf: valor('proto-cpf'),
      email: valor('proto-email'),
      telefone: valor('proto-tel'),
      tipoSolicitacao: valor('proto-tipo'),
      descricao: valor('proto-desc')
    };

    if (!corpo.nome || !corpo.cpf || !corpo.email || !corpo.tipoSolicitacao || !corpo.descricao) {
      mostrarFeedback('Preencha nome, CPF, e-mail, tipo e descrição da solicitação.', 'error');
      return;
    }

    submitBtn.disabled = true;
    mostrarFeedback('Enviando solicitação...', null);

    try {
      const protocolo = await API.post('/protocolos', { corpo: corpo });
      await enviarAnexos(protocolo.numero);
      mostrarFeedback('Solicitação enviada com sucesso! Guarde o número do seu protocolo: ' + protocolo.numero, 'success');
      limparFormulario();
    } catch (erro) {
      const campos = erro.dados && erro.dados.campos;
      if (campos && campos.length) {
        mostrarFeedback(campos.map(c => c.mensagem || c.message || c).join(' '), 'error');
      } else {
        mostrarFeedback(erro.message || 'Não foi possível enviar a solicitação. Tente novamente.', 'error');
      }
    } finally {
      submitBtn.disabled = false;
    }
  }

  if (submitBtn) submitBtn.addEventListener('click', enviarSolicitacao);
})();
