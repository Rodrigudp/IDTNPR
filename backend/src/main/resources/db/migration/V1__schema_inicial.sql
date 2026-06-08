-- =====================================================================
-- V1 - Schema inicial do back-end IDTNPR
-- =====================================================================

-- Usuários do painel administrativo
CREATE TABLE usuario (
    id          BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nome        VARCHAR(150) NOT NULL,
    email       VARCHAR(180) NOT NULL UNIQUE,
    senha       VARCHAR(100) NOT NULL,            -- hash BCrypt
    role        VARCHAR(30)  NOT NULL DEFAULT 'ADMIN',
    enabled     BOOLEAN      NOT NULL DEFAULT TRUE,
    criado_em   TIMESTAMP    NOT NULL DEFAULT now()
);

-- Solicitações (Protocolo Digital)
CREATE TABLE protocolo (
    id                BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    numero            VARCHAR(40)  NOT NULL UNIQUE,
    nome              VARCHAR(150) NOT NULL,
    cpf               VARCHAR(14)  NOT NULL,
    email             VARCHAR(180) NOT NULL,
    telefone          VARCHAR(20),
    tipo_solicitacao  VARCHAR(40)  NOT NULL,
    descricao         TEXT         NOT NULL,
    status            VARCHAR(30)  NOT NULL DEFAULT 'ABERTO',
    criado_em         TIMESTAMP    NOT NULL DEFAULT now(),
    atualizado_em     TIMESTAMP    NOT NULL DEFAULT now()
);

CREATE INDEX idx_protocolo_status ON protocolo (status);
CREATE INDEX idx_protocolo_criado_em ON protocolo (criado_em);

-- Anexos vinculados a um protocolo
CREATE TABLE anexo (
    id            BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    protocolo_id  BIGINT       NOT NULL REFERENCES protocolo (id) ON DELETE CASCADE,
    nome_original VARCHAR(255) NOT NULL,
    content_type  VARCHAR(120),
    tamanho_bytes BIGINT,
    caminho       VARCHAR(500) NOT NULL,
    criado_em     TIMESTAMP    NOT NULL DEFAULT now()
);

CREATE INDEX idx_anexo_protocolo ON anexo (protocolo_id);

-- Projetos exibidos na landing page
CREATE TABLE projeto (
    id            BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    titulo        VARCHAR(150) NOT NULL,
    descricao     TEXT,
    imagem_url    VARCHAR(500),
    link          VARCHAR(500),
    ordem         INT          NOT NULL DEFAULT 0,
    criado_em     TIMESTAMP    NOT NULL DEFAULT now(),
    atualizado_em TIMESTAMP    NOT NULL DEFAULT now()
);

-- Conteúdo editável do site (registro único, sempre id = 1)
CREATE TABLE conteudo_site (
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
    atualizado_em    TIMESTAMP NOT NULL DEFAULT now()
);

-- Mensagens do "Fale Conosco"
CREATE TABLE mensagem_contato (
    id        BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nome      VARCHAR(150) NOT NULL,
    email     VARCHAR(180) NOT NULL,
    telefone  VARCHAR(20),
    mensagem  TEXT         NOT NULL,
    lida      BOOLEAN      NOT NULL DEFAULT FALSE,
    criado_em TIMESTAMP    NOT NULL DEFAULT now()
);

-- Arquivos genéricos enviados pelo admin (logo, imagem hero, etc.)
CREATE TABLE arquivo (
    id            BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    nome_original VARCHAR(255) NOT NULL,
    content_type  VARCHAR(120),
    tamanho_bytes BIGINT,
    caminho       VARCHAR(500) NOT NULL,
    criado_em     TIMESTAMP    NOT NULL DEFAULT now()
);
