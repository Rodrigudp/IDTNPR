-- =====================================================================
-- banco.sql  —  Estrutura do banco de dados (MySQL / Locaweb)
-- ---------------------------------------------------------------------
-- Este arquivo cria as tabelas e o conteúdo inicial do site.
-- Você NÃO precisa rodar este arquivo manualmente: o "instalar.php" já
-- executa tudo daqui. Mas, se preferir, pode importá-lo pelo phpMyAdmin.
--
-- Tudo aqui é seguro de rodar mais de uma vez:
--   - CREATE TABLE IF NOT EXISTS  -> não recria tabelas existentes
--   - INSERT IGNORE               -> não duplica o conteúdo inicial
-- =====================================================================

-- Usuários do painel administrativo
CREATE TABLE IF NOT EXISTS usuario (
    id         BIGINT       AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(150) NOT NULL,
    email      VARCHAR(180) NOT NULL UNIQUE,
    senha      VARCHAR(100) NOT NULL,             -- hash BCrypt (nunca a senha pura)
    role       VARCHAR(30)  NOT NULL DEFAULT 'ADMIN',
    enabled    TINYINT(1)   NOT NULL DEFAULT 1,   -- 1 = ativo, 0 = desativado
    criado_em  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Solicitações (Protocolo Digital)
CREATE TABLE IF NOT EXISTS protocolo (
    id               BIGINT       AUTO_INCREMENT PRIMARY KEY,
    numero           VARCHAR(40)  NOT NULL UNIQUE,
    nome             VARCHAR(150) NOT NULL,
    cpf              VARCHAR(14)  NOT NULL,
    email            VARCHAR(180) NOT NULL,
    telefone         VARCHAR(20),
    tipo_solicitacao VARCHAR(40)  NOT NULL,
    descricao        TEXT         NOT NULL,
    status           VARCHAR(30)  NOT NULL DEFAULT 'ABERTO',
    criado_em        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_protocolo_status (status),
    INDEX idx_protocolo_criado_em (criado_em)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Anexos vinculados a um protocolo
CREATE TABLE IF NOT EXISTS anexo (
    id            BIGINT       AUTO_INCREMENT PRIMARY KEY,
    protocolo_id  BIGINT       NOT NULL,
    nome_original VARCHAR(255) NOT NULL,
    content_type  VARCHAR(120),
    tamanho_bytes BIGINT,
    caminho       VARCHAR(500) NOT NULL,
    criado_em     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_anexo_protocolo (protocolo_id),
    FOREIGN KEY (protocolo_id) REFERENCES protocolo (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Projetos exibidos na landing page
CREATE TABLE IF NOT EXISTS projeto (
    id            BIGINT       AUTO_INCREMENT PRIMARY KEY,
    titulo        VARCHAR(150) NOT NULL,
    descricao     TEXT,
    imagem_url    VARCHAR(500),
    link          VARCHAR(500),
    ordem         INT          NOT NULL DEFAULT 0,
    criado_em     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Conteúdo editável do site (registro único, sempre id = 1)
CREATE TABLE IF NOT EXISTS conteudo_site (
    id               BIGINT PRIMARY KEY,
    hero_title       VARCHAR(255),
    hero_highlight   VARCHAR(255),
    hero_desc        TEXT,
    hero_btn         VARCHAR(120),
    about_text       TEXT,
    feat1_title      VARCHAR(150),
    feat1_desc       TEXT,
    feat2_title      VARCHAR(150),
    feat2_desc       TEXT,
    feat3_title      VARCHAR(150),
    feat3_desc       TEXT,
    feat4_title      VARCHAR(150),
    feat4_desc       TEXT,
    cta_title        VARCHAR(255),
    cta_desc         TEXT,
    cta_btn          VARCHAR(120),
    contato_email    VARCHAR(180),
    contato_telefone VARCHAR(40),
    logo_url         VARCHAR(500),
    hero_img_url     VARCHAR(500),
    atualizado_em    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Mensagens do "Fale Conosco"
CREATE TABLE IF NOT EXISTS mensagem_contato (
    id        BIGINT       AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(150) NOT NULL,
    email     VARCHAR(180) NOT NULL,
    telefone  VARCHAR(20),
    mensagem  TEXT         NOT NULL,
    lida      TINYINT(1)   NOT NULL DEFAULT 0,
    criado_em TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Arquivos genéricos enviados pelo admin (logo, imagem hero, etc.)
CREATE TABLE IF NOT EXISTS arquivo (
    id            BIGINT       AUTO_INCREMENT PRIMARY KEY,
    nome_original VARCHAR(255) NOT NULL,
    content_type  VARCHAR(120),
    tamanho_bytes BIGINT,
    caminho       VARCHAR(500) NOT NULL,
    criado_em     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tentativas de login por IP (proteção contra força bruta)
CREATE TABLE IF NOT EXISTS login_tentativa (
    ip            VARCHAR(45) PRIMARY KEY,
    tentativas    INT         NOT NULL DEFAULT 0,
    inicio_janela TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------
-- Conteúdo inicial do site (mesmos textos padrão do admin.html)
-- ---------------------------------------------------------------------
INSERT IGNORE INTO conteudo_site (
    id, hero_title, hero_highlight, hero_desc, hero_btn, about_text,
    feat1_title, feat1_desc, feat2_title, feat2_desc,
    feat3_title, feat3_desc, feat4_title, feat4_desc,
    cta_title, cta_desc, cta_btn, contato_email, contato_telefone
) VALUES (
    1,
    'Seu município pode fazer parte do',
    'ecossistema de inovação',
    'Desenvolvemos soluções digitais para modernizar serviços públicos, promover transparência e aproximar cidadãos do governo através da tecnologia.',
    'Solicitar Reunião Técnica',
    'Atuamos na transformação digital do setor público, combinando tecnologia, gestão e conhecimento para gerar resultados reais para a sociedade.',
    'Transformação Digital Pública', 'Soluções digitais para modernizar serviços e processos públicos.',
    'Dados e Transparência',          'Inteligência de dados para decisões mais eficientes.',
    'Inovação e Gestão',              'Metodologias que aumentam a eficiência dos órgãos públicos.',
    'Capacitação Técnica',            'Formação e transferência de conhecimento para equipes.',
    'Parcerias que transformam cidades.',
    'Trabalhamos junto a órgãos públicos, instituições e especialistas para transformar realidades e gerar impacto positivo.',
    'QUERO SER PARCEIRO →',
    'contato@idtnpr.org.br',
    '(44) 99999-9999'
);

INSERT IGNORE INTO projeto (id, titulo, descricao, imagem_url, link, ordem) VALUES
    (1, 'Protocolo Digital', 'Plataforma para abertura, tramitação e acompanhamento de solicitações.', 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?q=80&w=800&auto=format&fit=crop', '#', 1),
    (2, 'Atendimento ao Cidadão', 'Centralização de canais de atendimento com mais agilidade.', 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800&auto=format&fit=crop', '#', 2),
    (3, 'Painel de Indicadores', 'Dashboards inteligentes para acompanhamento da gestão.', 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800&auto=format&fit=crop', '#', 3);
