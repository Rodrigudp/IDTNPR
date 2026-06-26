(function() {
  if (!API.estaLogado()) {
    window.location.href = '../login.php';
    return;
  }

  var conteudo = {};
  var projetos = [];

  var CAMPOS_TEXTO = {
    'hero-title': 'heroTitle',
    'hero-highlight': 'heroHighlight',
    'hero-desc': 'heroDesc',
    'hero-btn': 'heroBtn',
    'about-text': 'aboutText',
    'feat1-title': 'feat1Title',
    'feat1-desc': 'feat1Desc',
    'feat2-title': 'feat2Title',
    'feat2-desc': 'feat2Desc',
    'feat3-title': 'feat3Title',
    'feat3-desc': 'feat3Desc',
    'feat4-title': 'feat4Title',
    'feat4-desc': 'feat4Desc',
    'cta-title': 'ctaTitle',
    'cta-desc': 'ctaDesc',
    'cta-btn': 'ctaBtn'
  };

  function escapeHtml(value) {
    return String(value || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function tratarErro(error) {
    if (error && error.status === 401) {
      API.limparToken();
      window.location.href = '../login.php';
      return;
    }
    showToast((error && error.message) || 'Ocorreu um erro.');
  }

  function logout() {
    API.limparToken();
    window.location.href = '../login.php';
  }

  var navItems = document.querySelectorAll('.nav-item');
  var sections = document.querySelectorAll('.section');

  navItems.forEach(function(btn) {
    btn.addEventListener('click', function() {
      navItems.forEach(function(b) {
        b.classList.remove('active');
      });

      btn.classList.add('active');

      sections.forEach(function(s) {
        s.classList.remove('active');
      });

      document.getElementById('sec-' + btn.getAttribute('data-section')).classList.add('active');
    });
  });

  function loadTextos() {
    Object.keys(CAMPOS_TEXTO).forEach(function(id) {
      document.getElementById(id).value = conteudo[CAMPOS_TEXTO[id]] || '';
    });
  }

  function collectTextos() {
    Object.keys(CAMPOS_TEXTO).forEach(function(id) {
      conteudo[CAMPOS_TEXTO[id]] = document.getElementById(id).value;
    });
  }

  function loadContato() {
    document.getElementById('contact-email').value = conteudo.contatoEmail || '';
    document.getElementById('contact-phone').value = conteudo.contatoTelefone || '';
  }

  function collectContato() {
    conteudo.contatoEmail = document.getElementById('contact-email').value;
    conteudo.contatoTelefone = document.getElementById('contact-phone').value;
  }

  function renderProjetos() {
    var list = document.getElementById('project-list');

    if (!projetos || projetos.length === 0) {
      list.innerHTML = '<p style="color:var(--gray-400);font-size:0.88rem;padding:20px;text-align:center;">Nenhum projeto cadastrado</p>';
      return;
    }

    var html = '';

    for (var i = 0; i < projetos.length; i++) {
      var p = projetos[i];

      html += ''
        + '<div class="project-item">'
        + '<img class="project-thumb" src="' + escapeHtml(API.urlArquivo(p.imagemUrl)) + '" alt="' + escapeHtml(p.titulo) + '">'
        + '<div class="project-info">'
        + '<h4>' + escapeHtml(p.titulo) + '</h4>'
        + '<p>' + escapeHtml(p.descricao) + '</p>'
        + '</div>'
        + '<div class="project-actions">'
        + '<button class="btn-icon" data-edit="' + i + '" title="Editar">'
        + '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">'
        + '<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>'
        + '<path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>'
        + '</svg>'
        + '</button>'
        + '<button class="btn-icon danger" data-remove="' + i + '" title="Remover">'
        + '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">'
        + '<path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>'
        + '</svg>'
        + '</button>'
        + '</div>'
        + '</div>';
    }

    list.innerHTML = html;

    list.querySelectorAll('[data-edit]').forEach(function(btn) {
      btn.addEventListener('click', function() {
        editarProjeto(parseInt(this.getAttribute('data-edit')));
      });
    });

    list.querySelectorAll('[data-remove]').forEach(function(btn) {
      btn.addEventListener('click', function() {
        removerProjeto(parseInt(this.getAttribute('data-remove')));
      });
    });
  }

  function abrirModal() {
    document.getElementById('modal-title').textContent = 'Novo Projeto';
    document.getElementById('proj-edit-index').value = '-1';
    document.getElementById('proj-titulo').value = '';
    document.getElementById('proj-descricao').value = '';
    document.getElementById('proj-imagem').value = '';
    document.getElementById('proj-link').value = '';
    document.getElementById('modal-projeto').classList.add('show');
  }

  function editarProjeto(i) {
    var p = projetos[i];

    document.getElementById('modal-title').textContent = 'Editar Projeto';
    document.getElementById('proj-edit-index').value = i;
    document.getElementById('proj-titulo').value = p.titulo || '';
    document.getElementById('proj-descricao').value = p.descricao || '';
    document.getElementById('proj-imagem').value = p.imagemUrl || '';
    document.getElementById('proj-link').value = p.link || '';
    document.getElementById('modal-projeto').classList.add('show');
  }

  function fecharModal() {
    document.getElementById('modal-projeto').classList.remove('show');
  }

  async function salvarProjeto() {
    var idx = parseInt(document.getElementById('proj-edit-index').value);
    var titulo = document.getElementById('proj-titulo').value.trim();

    if (!titulo) {
      showToast('Informe o título do projeto.');
      return;
    }

    var corpo = {
      titulo: titulo,
      descricao: document.getElementById('proj-descricao').value,
      imagemUrl: document.getElementById('proj-imagem').value,
      link: document.getElementById('proj-link').value || '#',
      ordem: idx === -1 ? projetos.length : (projetos[idx].ordem || idx)
    };

    try {
      if (idx === -1) {
        await API.post('/admin/projetos', { corpo: corpo, autenticado: true });
      } else {
        await API.put('/admin/projetos/' + projetos[idx].id, { corpo: corpo, autenticado: true });
      }

      fecharModal();
      await recarregarProjetos();
      showToast('Projeto salvo com sucesso!');
    } catch (error) {
      tratarErro(error);
    }
  }

  async function removerProjeto(i) {
    if (!confirm('Remover este projeto?')) {
      return;
    }

    try {
      await API.del('/admin/projetos/' + projetos[i].id, { autenticado: true });
      await recarregarProjetos();
      showToast('Projeto removido.');
    } catch (error) {
      tratarErro(error);
    }
  }

  async function handleUpload(input, type) {
    var file = input.files[0];

    if (!file) {
      return;
    }

    try {
      var formData = new FormData();
      formData.append('arquivo', file);

      var result = await API.post('/admin/arquivos', { formData: formData, autenticado: true });

      conteudo[type === 'logo' ? 'logoUrl' : 'heroImgUrl'] = result.url;

      var preview = document.getElementById('preview-' + type);
      var zone = document.getElementById('zone-' + type);

      preview.src = API.urlArquivo(result.url);
      preview.style.display = 'block';

      zone.classList.add('has-image');
      zone.querySelector('p').textContent = file.name;

      showToast('Imagem carregada. Clique em Publicar Alterações para gravar.');
    } catch (error) {
      tratarErro(error);
    }
  }

  function loadImagens() {
    var mapa = { logo: 'logoUrl', hero: 'heroImgUrl' };

    Object.keys(mapa).forEach(function(type) {
      var url = conteudo[mapa[type]];
      if (!url) {
        return;
      }

      var preview = document.getElementById('preview-' + type);
      var zone = document.getElementById('zone-' + type);

      preview.src = API.urlArquivo(url);
      preview.style.display = 'block';

      zone.classList.add('has-image');
      zone.querySelector('p').textContent = 'Imagem carregada';
    });
  }

  function showToast(msg) {
    var toast = document.getElementById('toast');

    document.getElementById('toast-msg').textContent = msg;

    toast.classList.add('show');

    setTimeout(function() {
      toast.classList.remove('show');
    }, 3000);
  }

  async function publicar() {
    try {
      collectTextos();
      collectContato();

      await API.put('/admin/conteudo', { corpo: conteudo, autenticado: true });

      showToast('Alterações publicadas com sucesso!');
    } catch (error) {
      tratarErro(error);
    }
  }

  async function recarregarProjetos() {
    projetos = await API.get('/admin/projetos', { autenticado: true });
    renderProjetos();
  }

  async function carregarTudo() {
    try {
      var resultados = await Promise.all([
        API.get('/admin/conteudo', { autenticado: true }),
        API.get('/admin/projetos', { autenticado: true })
      ]);

      conteudo = resultados[0] || {};
      projetos = resultados[1] || [];

      loadTextos();
      loadContato();
      loadImagens();
      renderProjetos();
    } catch (error) {
      tratarErro(error);
    }
  }

  document.getElementById('btn-publicar').addEventListener('click', publicar);
  document.getElementById('btn-logout').addEventListener('click', logout);
  document.getElementById('btn-novo-projeto').addEventListener('click', abrirModal);
  document.getElementById('btn-cancelar').addEventListener('click', fecharModal);
  document.getElementById('btn-salvar-projeto').addEventListener('click', salvarProjeto);

  document.getElementById('modal-projeto').addEventListener('click', function(e) {
    if (e.target === this) {
      fecharModal();
    }
  });

  document.querySelectorAll('.upload-zone').forEach(function(zone) {
    zone.addEventListener('click', function(e) {
      if (e.target.tagName === 'INPUT') {
        return;
      }

      this.querySelector('input[type="file"]').click();
    });
  });

  document.getElementById('input-logo').addEventListener('change', function() {
    handleUpload(this, 'logo');
  });

  document.getElementById('input-hero').addEventListener('change', function() {
    handleUpload(this, 'hero');
  });

  carregarTudo();
})();
