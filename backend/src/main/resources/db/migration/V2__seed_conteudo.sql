-- =====================================================================
-- V2 - Conteúdo inicial do site (espelha os defaults do admin.html)
-- O usuário admin NÃO é criado aqui (a senha precisa ser gerada com BCrypt
-- em tempo de execução); isso é feito pelo DataSeeder.
-- =====================================================================

INSERT INTO conteudo_site (
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

INSERT INTO projeto (titulo, descricao, imagem_url, link, ordem) VALUES
    ('Protocolo Digital', 'Plataforma para abertura, tramitação e acompanhamento de solicitações.', 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?q=80&w=800&auto=format&fit=crop', '#', 1),
    ('Atendimento ao Cidadão', 'Centralização de canais de atendimento com mais agilidade.', 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800&auto=format&fit=crop', '#', 2),
    ('Painel de Indicadores', 'Dashboards inteligentes para acompanhamento da gestão.', 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800&auto=format&fit=crop', '#', 3);
