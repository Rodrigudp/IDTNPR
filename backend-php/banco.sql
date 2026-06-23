CREATE TABLE IF NOT EXISTS usuarios (
    usuid INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    usunome VARCHAR(100) NOT NULL,
    usuemail VARCHAR(150) NOT NULL UNIQUE,
    ususenha VARCHAR(255) NOT NULL,
    usuinclusao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    usuexclusao TIMESTAMP NULL
);

CREATE TABLE IF NOT EXISTS tipos_solicitacao (
    tipid INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    tipnome VARCHAR(100) NOT NULL,
    tipsigla VARCHAR(50) NOT NULL UNIQUE,
    tipativo BOOLEAN NOT NULL DEFAULT TRUE,
    tipinclusao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tipexclusao TIMESTAMP NULL
);

CREATE TABLE IF NOT EXISTS status_solicitacao (
    stsid INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    stsnome VARCHAR(100) NOT NULL,
    stssigla VARCHAR(50) NOT NULL UNIQUE,
    stsordem INTEGER NOT NULL DEFAULT 0,
    stsativo BOOLEAN NOT NULL DEFAULT TRUE,
    stsinclusao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    stsexclusao TIMESTAMP NULL
);

CREATE TABLE IF NOT EXISTS solicitacoes (
    solid INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    solnumero VARCHAR(40) NOT NULL UNIQUE,
    solnome VARCHAR(150) NOT NULL,
    solcpf VARCHAR(14) NOT NULL,
    solemail VARCHAR(180) NOT NULL,
    soltelefone VARCHAR(20) NULL,
    soltipoid INTEGER NOT NULL,
    solstatusid INTEGER NOT NULL,
    soldescricao TEXT NOT NULL,
    solinclusao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    solexclusao TIMESTAMP NULL,
    CONSTRAINT fk_solicitacoes_tipos
        FOREIGN KEY (soltipoid) REFERENCES tipos_solicitacao (tipid),
    CONSTRAINT fk_solicitacoes_status
        FOREIGN KEY (solstatusid) REFERENCES status_solicitacao (stsid)
);

CREATE TABLE IF NOT EXISTS anexos_solicitacoes (
    anxid INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    anxsolicitacaoid INTEGER NOT NULL,
    anxnomeoriginal VARCHAR(255) NOT NULL,
    anxcontenttype VARCHAR(120) NULL,
    anxtamanho BIGINT NULL,
    anxcaminho VARCHAR(500) NOT NULL,
    anxinclusao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    anxexclusao TIMESTAMP NULL,
    CONSTRAINT fk_anexos_solicitacoes
        FOREIGN KEY (anxsolicitacaoid) REFERENCES solicitacoes (solid)
);

CREATE TABLE IF NOT EXISTS conteudos_site (
    consid INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    constituloprincipal VARCHAR(255) NULL,
    consdestaqueprincipal VARCHAR(255) NULL,
    consdescricaoprincipal TEXT NULL,
    constextobotao VARCHAR(120) NULL,
    conssobre TEXT NULL,
    consrecurso1titulo VARCHAR(150) NULL,
    consrecurso1descricao TEXT NULL,
    consrecurso2titulo VARCHAR(150) NULL,
    consrecurso2descricao TEXT NULL,
    consrecurso3titulo VARCHAR(150) NULL,
    consrecurso3descricao TEXT NULL,
    consrecurso4titulo VARCHAR(150) NULL,
    consrecurso4descricao TEXT NULL,
    constitulochamada VARCHAR(255) NULL,
    consdescricaochamada TEXT NULL,
    constextobotaochamada VARCHAR(120) NULL,
    consemailcontato VARCHAR(180) NULL,
    constelefonecontato VARCHAR(40) NULL,
    conslogourl VARCHAR(500) NULL,
    consimagemprincipalurl VARCHAR(500) NULL,
    consinclusao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    consexclusao TIMESTAMP NULL
);

CREATE TABLE IF NOT EXISTS projetos (
    prjid INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    prjtitulo VARCHAR(150) NOT NULL,
    prjdescricao TEXT NULL,
    prjimagemurl VARCHAR(500) NULL,
    prjlink VARCHAR(500) NULL,
    prjordem INTEGER NOT NULL DEFAULT 0,
    prjinclusao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    prjexclusao TIMESTAMP NULL
);

CREATE TABLE IF NOT EXISTS mensagens_contato (
    menid INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    mennome VARCHAR(150) NOT NULL,
    menemail VARCHAR(180) NOT NULL,
    mentelefone VARCHAR(20) NULL,
    menmensagem TEXT NOT NULL,
    menlida BOOLEAN NOT NULL DEFAULT FALSE,
    meninclusao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    menexclusao TIMESTAMP NULL
);

CREATE TABLE IF NOT EXISTS arquivos (
    arqid INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    arqnomeoriginal VARCHAR(255) NOT NULL,
    arqcontenttype VARCHAR(120) NULL,
    arqtamanho BIGINT NULL,
    arqcaminho VARCHAR(500) NOT NULL,
    arqinclusao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    arqexclusao TIMESTAMP NULL
);

CREATE TABLE IF NOT EXISTS tentativas_login (
    tenid INTEGER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    tenip VARCHAR(45) NOT NULL,
    tentativas INTEGER NOT NULL DEFAULT 0,
    teniniciojanela TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    teninclusao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tenexclusao TIMESTAMP NULL
);

INSERT INTO tipos_solicitacao (tipnome, tipsigla, tipativo, tipinclusao)
VALUES
    ('Informacao', 'INFORMACAO', TRUE, NOW()),
    ('Solicitacao de servico', 'SOLICITACAO_SERVICO', TRUE, NOW()),
    ('Reclamacao', 'RECLAMACAO', TRUE, NOW()),
    ('Denuncia', 'DENUNCIA', TRUE, NOW()),
    ('Elogio', 'ELOGIO', TRUE, NOW()),
    ('Outro', 'OUTRO', TRUE, NOW())
ON CONFLICT (tipsigla) DO NOTHING;

INSERT INTO status_solicitacao (stsnome, stssigla, stsordem, stsativo, stsinclusao)
VALUES
    ('Aberto', 'ABERTO', 1, TRUE, NOW()),
    ('Em analise', 'EM_ANALISE', 2, TRUE, NOW()),
    ('Concluido', 'CONCLUIDO', 3, TRUE, NOW()),
    ('Arquivado', 'ARQUIVADO', 4, TRUE, NOW())
ON CONFLICT (stssigla) DO NOTHING;
