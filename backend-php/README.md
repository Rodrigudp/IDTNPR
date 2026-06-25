# IDTNPR API — Back-end em PHP

Back-end do site do **Instituto de Desenvolvimento de Tecnologias do Noroeste Paranaense
(IDTNPR)**, escrito em **PHP puro** para rodar facilmente em hospedagem compartilhada
(como a **Locaweb**).

Esta é a versão PHP do back-end que antes estava em Java (Spring Boot). Ela faz **as mesmas
coisas** e responde nos **mesmos endereços** (`/api/...`), mas com um código bem mais simples:
sem frameworks, sem Composer, sem instalar nada — só PHP e MySQL.

> A versão antiga em Java continua na pasta `../backend` (para consulta). Quando esta versão
> PHP estiver no ar e testada, vocês podem apagar a pasta Java.

---

## Por que é simples de entender

- **PHP puro, estilo procedural** (funções, não classes complicadas).
- **Sem dependências externas**: usa só o que já vem no PHP (PDO, `password_hash`, `fileinfo`).
- **Um arquivo por assunto**, igual era organizado no Java:
  - `rotas/auth.php` — login
  - `rotas/protocolos.php` — Protocolo Digital
  - `rotas/conteudo.php` — textos e projetos do site
  - `rotas/contato.php` — "Fale Conosco"
  - `rotas/arquivos.php` — upload/download de imagens
- O arquivo **`index.php`** tem a "tabela de rotas": é o mapa do sistema inteiro.

---

## O que você precisa

- **PHP 7.4 ou superior** (a Locaweb oferece; ative no painel se necessário).
- **Banco de dados MySQL 5.7+** no DBaaS informado pelo professor.
- Extensões do PHP: `pdo_mysql` e `fileinfo` (já vêm ativas por padrão).

---

## Estrutura dos arquivos

```
backend-php/
├── index.php           # Porta de entrada: recebe TODAS as requisições e direciona
├── config.php          # Lê o arquivo .env (configurações e senhas)
├── instalar.php        # Roda 1x: cria tabelas, conteúdo inicial e o usuário admin
├── banco.sql           # Estrutura do banco (usada pelo instalar.php)
├── .env.example        # Modelo de configuração (copie para .env)
├── .htaccess           # Configuração do servidor Apache (Locaweb)
├── lib/                # "Peças" reutilizáveis do sistema
│   ├── http.php        #   ler requisição / responder em JSON
│   ├── banco.php       #   conexão com o MySQL
│   ├── validacao.php   #   validação dos formulários
│   ├── jwt.php         #   gera/valida o token de login
│   ├── auth.php        #   protege as rotas do admin
│   ├── login.php       #   bloqueio de força bruta no login
│   └── armazenamento.php #  salva/lê arquivos enviados
├── rotas/              # As funcionalidades, uma por assunto
│   ├── auth.php
│   ├── protocolos.php
│   ├── conteudo.php
│   ├── contato.php
│   └── arquivos.php
└── uploads/            # Arquivos enviados pelos usuários (criada automaticamente)
```

---

## Como colocar no ar na Locaweb (passo a passo)

1. **Use o banco MySQL DBaaS informado pelo professor**. O acesso é feito pela aplicação como cliente MySQL externo, usando host, porta, nome do banco, usuário e senha. Não há phpMyAdmin para esse serviço.

2. **Envie os arquivos** desta pasta (`backend-php/`) para o servidor, dentro de uma pasta
   chamada **`api`** na raiz do site. Assim os endereços ficam, por exemplo:
   `https://seusite.com.br/api/protocolos`
   (Pode enviar por FTP, pelo Gerenciador de Arquivos da Locaweb, etc.)

3. **Crie o arquivo `.env`**: copie o `.env.example` para `.env` e preencha a senha real do
   banco DBaaS, uma `ADMIN_SENHA` forte e um `JWT_SECRET` longo e aleatório.

4. **Rode a instalação**: abra no navegador
   `https://seusite.com.br/api/instalar.php`
   Deve aparecer "Instalação concluída!".

5. **Apague o `instalar.php`** do servidor (por segurança, ele não é mais necessário).

6. **Teste**: abra `https://seusite.com.br/api/health` — deve responder `{"status":"ok"}`.

> **Subdomínio?** Se preferir usar `api.seusite.com.br`, aponte o subdomínio para uma pasta
> e coloque os arquivos lá; os endereços continuam funcionando, pois o roteador localiza o
> trecho `/api/` automaticamente.

---

## Como rodar no seu computador (para testar)

Com o PHP instalado, dentro da pasta `backend-php`:

```bash
php -S localhost:8080
```

Crie o `.env` (apontando para um MySQL local), rode `http://localhost:8080/instalar.php`
e depois teste `http://localhost:8080/api/health`.

---

## Login do admin (como funciona)

```bash
# 1) Faz login e recebe o token
curl -X POST https://seusite.com.br/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@idtnpr.org.br","senha":"SUA_SENHA"}'

# Resposta: { "accessToken": "xxxxx", "tokenType": "Bearer", "expiresIn": 28800 }

# 2) Usa o token nas rotas de admin
curl https://seusite.com.br/api/admin/protocolos \
  -H "Authorization: Bearer xxxxx"
```

---

## Endpoints (endereços da API)

| Método | Rota | Acesso | O que faz |
|--------|------|--------|-----------|
| GET | `/api/health` | público | Diz se a API está no ar |
| POST | `/api/auth/login` | público | Faz login e devolve o token |
| POST | `/api/protocolos` | público | Abre uma solicitação |
| GET | `/api/protocolos/{numero}` | público | Acompanha solicitação (CPF mascarado) |
| POST | `/api/protocolos/{numero}/anexos` | público | Anexa arquivo (campo `arquivo`) |
| GET | `/api/conteudo` | público | Textos + projetos da landing |
| GET | `/api/projetos` | público | Lista de projetos |
| POST | `/api/contato` | público | Envia mensagem (Fale Conosco) |
| GET | `/api/arquivos/{id}` | público | Baixa um arquivo |
| GET | `/api/admin/protocolos` | ADMIN | Lista protocolos (filtro `?status=`, paginação `?page=&size=`) |
| GET | `/api/admin/protocolos/{numero}` | ADMIN | Detalha um protocolo (CPF completo) |
| PATCH | `/api/admin/protocolos/{numero}/status` | ADMIN | Muda o status |
| GET | `/api/admin/conteudo` | ADMIN | Lê os textos do site |
| PUT | `/api/admin/conteudo` | ADMIN | Salva os textos do site |
| GET | `/api/admin/projetos` | ADMIN | Lista projetos |
| POST | `/api/admin/projetos` | ADMIN | Cria projeto |
| PUT | `/api/admin/projetos/{id}` | ADMIN | Edita projeto |
| DELETE | `/api/admin/projetos/{id}` | ADMIN | Remove projeto |
| GET | `/api/admin/mensagens` | ADMIN | Caixa de entrada (filtro `?lida=true/false`) |
| PATCH | `/api/admin/mensagens/{id}/lida` | ADMIN | Marca mensagem como lida |
| POST | `/api/admin/arquivos` | ADMIN | Envia uma imagem (campo `arquivo`) |

**Tipos de solicitação** (`tipoSolicitacao`): `INFORMACAO`, `SOLICITACAO_SERVICO`,
`RECLAMACAO`, `DENUNCIA`, `ELOGIO`, `OUTRO`.

**Status do protocolo**: `ABERTO` → `EM_ANALISE` → `CONCLUIDO` → `ARQUIVADO`
(as mudanças válidas estão em `transicoes_permitidas()`, no arquivo `rotas/protocolos.php`).

---

## Segurança (o que já vem pronto)

- **Login com senha criptografada** (BCrypt, via `password_hash`/`password_verify`).
- **Token de acesso (JWT)** assinado com uma senha secreta (`JWT_SECRET`).
- **Bloqueio de força bruta**: 5 tentativas erradas / 15 min por IP → resposta `429`.
- **Mensagem de erro genérica** no login (não revela se o e-mail existe).
- **Número de protocolo imprevisível** (aleatório) — evita que alguém "adivinhe" protocolos
  de outras pessoas.
- **CPF mascarado** na consulta pública; CPF completo só nas rotas de admin.
- **Upload seguro**: confere o tipo real do arquivo (não confia no navegador), só aceita
  PDF/JPG/PNG (anexos) e PNG/JPG/WEBP/GIF (imagens do site), nomes aleatórios e limite de 10 MB.
- **Proteção contra SQL injection** (PDO com prepared statements) e contra acesso direto aos
  arquivos internos.

### Antes de ir para produção
- Preencha um `JWT_SECRET` **longo e aleatório** no `.env`.
- Use uma `ADMIN_SENHA` forte e troque-a após o primeiro acesso.
- Sirva o site **por HTTPS** (a Locaweb oferece certificado gratuito).
- Em `CORS_ORIGINS`, coloque **apenas** o endereço real do site.
- **Apague o `instalar.php`** depois de instalar.

---

## De onde veio cada parte (Java → PHP)

Para quem conhecia a versão antiga, este é o "de-para":

| Java (Spring Boot) | PHP (aqui) |
|--------------------|------------|
| `AuthController` / `TokenService` | `rotas/auth.php` + `lib/jwt.php` |
| `LoginAttemptService` | `lib/login.php` |
| `ProtocoloController` / `ProtocoloService` | `rotas/protocolos.php` |
| `ConteudoController` / `ConteudoService` | `rotas/conteudo.php` |
| `ContatoController` / `ContatoService` | `rotas/contato.php` |
| `ArquivoController` / `ArmazenamentoService` | `rotas/arquivos.php` + `lib/armazenamento.php` |
| `SecurityConfig` (CORS, rotas protegidas) | `index.php` + `lib/auth.php` |
| `ApiExceptionHandler` (erros padronizados) | `lib/http.php` |
| Migrations Flyway (`V1`, `V2`) | `banco.sql` |
| `DataSeeder` (cria o admin) | `instalar.php` |
| `application.yml` / `.env` | `.env` (lido por `config.php`) |

### Principais diferenças (de propósito, para simplificar)
- **Banco**: era PostgreSQL, agora é **MySQL** (padrão da Locaweb).
- **Token**: era assinado com chaves RSA (precisava gerar arquivos `.pem`); agora é assinado
  com uma **senha secreta (HMAC)** — o formato do token para o cliente é o mesmo.
- **Sem Swagger**: a documentação dos endpoints é esta tabela no README.

---

## Observação sobre o front-end

As páginas `admin.html` e `protocolo.html` ainda **não** conversam com a API (o `admin.html`
salva tudo no `localStorage` do navegador). Ligar o front-end a esta API é o próximo passo —
veja a seção "Integração com o frontend" no `../backend/TAREFAS.md`. Esta API já está pronta
para receber essas chamadas.
