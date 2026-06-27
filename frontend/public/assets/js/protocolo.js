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
      sunIcon.style.display = 'none';
      moonIcon.style.display = 'block';
    } else {
      sunIcon.style.display = 'block';
      moonIcon.style.display = 'none';
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

  // ---- Acompanhamento de protocolo ----
  const trackSubmit  = document.getElementById('track-submit');
  const trackNumero  = document.getElementById('track-numero');
  const trackFeedback = document.getElementById('track-feedback');
  const trackResult  = document.getElementById('track-result');

  const tiposLabel = {
    INFORMACAO:        'Pedido de Informação',
    SOLICITACAO_SERVICO: 'Solicitação de Serviço',
    RECLAMACAO:        'Reclamação',
    DENUNCIA:          'Denúncia',
    ELOGIO:            'Elogio',
    OUTRO:             'Outro'
  };

  function formatarData(iso) {
    if (!iso) return '—';
    return new Date(iso).toLocaleString('pt-BR', {
      day: '2-digit', month: '2-digit', year: 'numeric',
      hour: '2-digit', minute: '2-digit'
    });
  }

  function mostrarTrackFeedback(msg, tipo) {
    if (!trackFeedback) return;
    trackFeedback.textContent = msg || '';
    trackFeedback.classList.remove('error', 'success');
    if (tipo) trackFeedback.classList.add(tipo);
  }

  function exibirResultado(p) {
    document.getElementById('track-result-numero').textContent  = p.numero;
    document.getElementById('track-result-nome').textContent    = p.nome;
    document.getElementById('track-result-cpf').textContent     = p.cpf;
    document.getElementById('track-result-tipo').textContent    = tiposLabel[p.tipoSolicitacao] || p.tipoSolicitacao;
    document.getElementById('track-result-data').textContent    = formatarData(p.criadoEm);
    document.getElementById('track-result-atualizado').textContent = formatarData(p.atualizadoEm);
    document.getElementById('track-result-desc').textContent    = p.descricao;

    const badge = document.getElementById('track-result-status');
    badge.textContent = p.status.replace('_', ' ');
    badge.className = 'track-value track-status-badge status-' + p.status;

    const anexosSection = document.getElementById('track-anexos-section');
    const anexosList    = document.getElementById('track-result-anexos');
    if (p.anexos && p.anexos.length > 0) {
      anexosList.innerHTML = '';
      p.anexos.forEach(function(a) {
        const li = document.createElement('li');
        li.textContent = a.nomeOriginal + ' (' + (a.contentType || '?') + ')';
        anexosList.appendChild(li);
      });
      anexosSection.style.display = '';
    } else {
      anexosSection.style.display = 'none';
    }

    trackResult.style.display = '';
  }

  async function consultarProtocolo() {
    const numero = trackNumero ? trackNumero.value.trim() : '';
    if (!numero) {
      mostrarTrackFeedback('Digite o número do protocolo.', 'error');
      return;
    }

    trackSubmit.disabled = true;
    mostrarTrackFeedback('Consultando...', null);
    trackResult.style.display = 'none';

    try {
      const protocolo = await API.get('/protocolos/' + encodeURIComponent(numero));
      mostrarTrackFeedback('', null);
      exibirResultado(protocolo);
    } catch (erro) {
      trackResult.style.display = 'none';
      mostrarTrackFeedback(
        erro.status === 404
          ? 'Protocolo não encontrado. Verifique o número e tente novamente.'
          : (erro.message || 'Não foi possível consultar. Tente novamente.'),
        'error'
      );
    } finally {
      trackSubmit.disabled = false;
    }
  }

  if (trackSubmit) trackSubmit.addEventListener('click', consultarProtocolo);
  if (trackNumero) {
    trackNumero.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') consultarProtocolo();
    });
  }
