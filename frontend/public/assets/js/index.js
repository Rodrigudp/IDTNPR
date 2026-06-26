(function() {
  function aplicarConteudo(t, projetos) {
    t = t || {};

    const heroTitle = document.querySelector('.hero-text h2');
    if (heroTitle && t.heroTitle) {
      heroTitle.innerHTML = t.heroTitle + ' <span>' + (t.heroHighlight || '') + '</span>';
    }
    const heroDesc = document.querySelector('.hero-text p');
    if (heroDesc && t.heroDesc) heroDesc.textContent = t.heroDesc;
    const heroBtn = document.querySelector('.hero-text .btn-primary');
    if (heroBtn && t.heroBtn) heroBtn.textContent = t.heroBtn;

    const aboutText = document.querySelector('.about-text p');
    if (aboutText && t.aboutText) aboutText.textContent = t.aboutText;

    const features = document.querySelectorAll('.feature');
    if (features.length >= 4) {
      if (t.feat1Title) features[0].querySelector('h3').textContent = t.feat1Title;
      if (t.feat1Desc) features[0].querySelector('p').textContent = t.feat1Desc;
      if (t.feat2Title) features[1].querySelector('h3').textContent = t.feat2Title;
      if (t.feat2Desc) features[1].querySelector('p').textContent = t.feat2Desc;
      if (t.feat3Title) features[2].querySelector('h3').textContent = t.feat3Title;
      if (t.feat3Desc) features[2].querySelector('p').textContent = t.feat3Desc;
      if (t.feat4Title) features[3].querySelector('h3').textContent = t.feat4Title;
      if (t.feat4Desc) features[3].querySelector('p').textContent = t.feat4Desc;
    }

    const ctaH2 = document.querySelector('.cta h2');
    if (ctaH2 && t.ctaTitle) ctaH2.textContent = t.ctaTitle;
    const ctaP = document.querySelector('.cta p');
    if (ctaP && t.ctaDesc) ctaP.textContent = t.ctaDesc;
    const ctaBtn = document.querySelector('.cta .btn-outline');
    if (ctaBtn && t.ctaBtn) ctaBtn.textContent = t.ctaBtn;

    if (projetos && projetos.length > 0) {
      const grid = document.querySelector('#main-project-grid');
      if (grid) {
        grid.innerHTML = projetos.map(p => `
          <div class="project-card reveal fade-bottom">
            <div class="project-img-wrapper">
              <img src="${API.urlArquivo(p.imagemUrl)}" alt="${p.titulo || ''}" loading="lazy">
            </div>
            <h3>${p.titulo || ''}</h3>
            <p>${p.descricao || ''}</p>
            <a href="${p.link || '#'}">Saiba mais <span>→</span></a>
          </div>
        `).join('');
      }
    }

    if (t.contatoEmail) {
      const el = document.getElementById('footer-email');
      if (el) el.textContent = t.contatoEmail;
    }
    if (t.contatoTelefone) {
      const el = document.getElementById('footer-phone');
      if (el) el.textContent = t.contatoTelefone;
    }

    if (t.logoUrl) {
      document.querySelectorAll('#site-logo, .footer-logo img').forEach(el => el.src = API.urlArquivo(t.logoUrl));
    }
    if (t.heroImgUrl) {
      const heroImg = document.getElementById('hero-img-element');
      if (heroImg) heroImg.src = API.urlArquivo(t.heroImgUrl);
    }
  }

  API.get('/conteudo')
    .then(resposta => aplicarConteudo(resposta.conteudo, resposta.projetos))
    .catch(erro => console.warn('Nao foi possivel carregar o conteudo da API:', erro.message));

  const themeToggle = document.getElementById('theme-toggle');
  const sunIcon = document.getElementById('theme-icon-sun');
  const moonIcon = document.getElementById('theme-icon-moon');
  
  function getPreferredTheme() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) return savedTheme;
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

  const initialTheme = getPreferredTheme();
  setTheme(initialTheme);

  themeToggle.addEventListener('click', () => {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    setTheme(newTheme);
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
      if (navContainer.classList.contains('active')) {
        toggleMenu();
      }
    });
  });

  const header = document.querySelector('.header');
  const backToTopBtn = document.getElementById('back-to-top');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 40) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }

    if (window.scrollY > 500) {
      backToTopBtn.classList.add('show');
    } else {
      backToTopBtn.classList.remove('show');
    }
  });

  backToTopBtn.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  const scrollIndicator = document.getElementById('scroll-indicator');
  if (scrollIndicator) {
    scrollIndicator.addEventListener('click', () => {
      const target = document.getElementById('sobre');
      if (target) {
        target.scrollIntoView({ behavior: 'smooth' });
      }
    });
  }

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (!prefersReducedMotion) {
    const revealElements = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('active');
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });

    revealElements.forEach(element => {
      revealObserver.observe(element);
    });
  } else {
    document.querySelectorAll('.reveal').forEach(el => el.classList.add('active'));
  }

})();
