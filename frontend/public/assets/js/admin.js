(function() {
  var ENDPOINT = window.location.pathname;

  var siteData = window.IDTNPR_ADMIN_DATA || {};

  function escapeHtml(value) {
    return String(value || '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  async function saveData(data) {
    var response = await fetch(ENDPOINT + '?action=save', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    });

    var result = await response.json();

    if (!response.ok || !result.ok) {
      throw new Error(result.message || 'Erro ao salvar alterações.');
    }

    return result;
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
    var t = siteData.textos;

    document.getElementById('hero-title').value = t.heroTitle || '';
    document.getElementById('hero-highlight').value = t.heroHighlight || '';
    document.getElementById('hero-desc').value = t.heroDesc || '';
    document.getElementById('hero-btn').value = t.heroBtn || '';
    document.getElementById('about-text').value = t.aboutText || '';
    document.getElementById('feat1-title').value = t.feat1Title || '';
    document.getElementById('feat1-desc').value = t.feat1Desc || '';
    document.getElementById('feat2-title').value = t.feat2Title || '';
    document.getElementById('feat2-desc').value = t.feat2Desc || '';
    document.getElementById('feat3-title').value = t.feat3Title || '';
    document.getElementById('feat3-desc').value = t.feat3Desc || '';
    document.getElementById('feat4-title').value = t.feat4Title || '';
    document.getElementById('feat4-desc').value = t.feat4Desc || '';
    document.getElementById('cta-title').value = t.ctaTitle || '';
    document.getElementById('cta-desc').value = t.ctaDesc || '';
    document.getElementById('cta-btn').value = t.ctaBtn || '';
  }

  function collectTextos() {
    siteData.textos.heroTitle = document.getElementById('hero-title').value;
    siteData.textos.heroHighlight = document.getElementById('hero-highlight').value;
    siteData.textos.heroDesc = document.getElementById('hero-desc').value;
    siteData.textos.heroBtn = document.getElementById('hero-btn').value;
    siteData.textos.aboutText = document.getElementById('about-text').value;
    siteData.textos.feat1Title = document.getElementById('feat1-title').value;
    siteData.textos.feat1Desc = document.getElementById('feat1-desc').value;
    siteData.textos.feat2Title = document.getElementById('feat2-title').value;
    siteData.textos.feat2Desc = document.getElementById('feat2-desc').value;
    siteData.textos.feat3Title = document.getElementById('feat3-title').value;
    siteData.textos.feat3Desc = document.getElementById('feat3-desc').value;
    siteData.textos.feat4Title = document.getElementById('feat4-title').value;
    siteData.textos.feat4Desc = document.getElementById('feat4-desc').value;
    siteData.textos.ctaTitle = document.getElementById('cta-title').value;
    siteData.textos.ctaDesc = document.getElementById('cta-desc').value;
    siteData.textos.ctaBtn = document.getElementById('cta-btn').value;
  }

  function loadContato() {
    document.getElementById('contact-email').value = siteData.contato.email || '';
    document.getElementById('contact-phone').value = siteData.contato.phone || '';
  }

  function collectContato() {
    siteData.contato.email = document.getElementById('contact-email').value;
    siteData.contato.phone = document.getElementById('contact-phone').value;
  }

  function renderProjetos() {
    var list = document.getElementById('project-list');

    if (!siteData.projetos || siteData.projetos.length === 0) {
      list.innerHTML = '<p style="color:var(--gray-400);font-size:0.88rem;padding:20px;text-align:center;">Nenhum projeto cadastrado</p>';
      return;
    }

    var html = '';

    for (var i = 0; i < siteData.projetos.length; i++) {
      var p = siteData.projetos[i];

      html += ''
        + '<div class="project-item">'
        + '<img class="project-thumb" src="' + escapeHtml(p.imagem) + '" alt="' + escapeHtml(p.titulo) + '">'
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
    var p = siteData.projetos[i];

    document.getElementById('modal-title').textContent = 'Editar Projeto';
    document.getElementById('proj-edit-index').value = i;
    document.getElementById('proj-titulo').value = p.titulo || '';
    document.getElementById('proj-descricao').value = p.descricao || '';
    document.getElementById('proj-imagem').value = p.imagem || '';
    document.getElementById('proj-link').value = p.link || '';
    document.getElementById('modal-projeto').classList.add('show');
  }

  function fecharModal() {
    document.getElementById('modal-projeto').classList.remove('show');
  }

  function salvarProjeto() {
    var idx = parseInt(document.getElementById('proj-edit-index').value);
    var titulo = document.getElementById('proj-titulo').value.trim();

    if (!titulo) {
      showToast('Informe o título do projeto.');
      return;
    }

    var projeto = {
      titulo: titulo,
      descricao: document.getElementById('proj-descricao').value,
      imagem: document.getElementById('proj-imagem').value,
      link: document.getElementById('proj-link').value || '#'
    };

    if (idx === -1) {
      siteData.projetos.push(projeto);
    } else {
      siteData.projetos[idx] = projeto;
    }

    fecharModal();
    renderProjetos();
    showToast('Projeto salvo. Clique em Publicar Alterações para gravar.');
  }

  function removerProjeto(i) {
    if (confirm('Remover este projeto?')) {
      siteData.projetos.splice(i, 1);
      renderProjetos();
      showToast('Projeto removido. Clique em Publicar Alterações para gravar.');
    }
  }

  async function handleUpload(input, type) {
    var file = input.files[0];

    if (!file) {
      return;
    }

    try {
      var formData = new FormData();
      formData.append('image', file);
      formData.append('type', type);

      var response = await fetch(ENDPOINT + '?action=upload', {
        method: 'POST',
        body: formData
      });

      var result = await response.json();

      if (!response.ok || !result.ok) {
        throw new Error(result.message || 'Erro ao enviar imagem.');
      }

      siteData.imagens[type] = result.url;

      var preview = document.getElementById('preview-' + type);
      var zone = document.getElementById('zone-' + type);

      preview.src = result.url;
      preview.style.display = 'block';

      zone.classList.add('has-image');
      zone.querySelector('p').textContent = file.name;

      showToast('Imagem carregada. Clique em Publicar Alterações para gravar.');
    } catch (error) {
      showToast(error.message);
    }
  }

  function loadImagens() {
    ['logo', 'hero'].forEach(function(type) {
      if (siteData.imagens && siteData.imagens[type]) {
        var preview = document.getElementById('preview-' + type);
        var zone = document.getElementById('zone-' + type);

        preview.src = siteData.imagens[type];
        preview.style.display = 'block';

        zone.classList.add('has-image');
        zone.querySelector('p').textContent = 'Imagem carregada';
      }
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

      await saveData(siteData);

      showToast('Alterações publicadas com sucesso!');
    } catch (error) {
      showToast(error.message);
    }
  }

  document.getElementById('btn-publicar').addEventListener('click', publicar);
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

  loadTextos();
  loadContato();
  renderProjetos();
  loadImagens();
})();
