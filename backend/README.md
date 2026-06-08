# IDTNPR API — Back-end

Back-end do site do **Instituto de Desenvolvimento de Tecnologias do Noroeste Paranaense (IDTNPR)**.
Expõe uma API REST para o Protocolo Digital, o conteúdo editável do site, o "Fale Conosco" e a
autenticação do painel administrativo.

## Stack

- **Java 21** + **Spring Boot 4.0.x** (Spring Framework 7 / Spring Security 7)
- **Maven** (build)
- **PostgreSQL** + **Flyway** (migrations)
- **JWT stateless** via OAuth2 Resource Server (chaves RSA)
- **springdoc-openapi** (Swagger UI)

## Estrutura (pacotes)

```
br.org.idtnpr
├── config       # SecurityConfig, OpenApiConfig, DataSeeder, propriedades (RSA, app)
├── auth         # login + emissão de JWT
├── usuario      # usuários do admin + UserDetailsService
├── protocolo    # domínio principal: solicitações e anexos
├── conteudo     # textos do site + projetos (substitui o localStorage)
├── contato      # "Fale Conosco"
├── arquivo      # upload/download de arquivos (logo, imagem hero)
└── common       # exceptions e tratamento de erros padronizado
```

## Pré-requisitos

1. **JDK 21** instalado (`java -version`).
2. **Maven 3.9+** (`mvn -v`).
3. **PostgreSQL** rodando. Via Docker:
   ```bash
   docker run --name idtnpr-db -e POSTGRES_DB=idtnpr -e POSTGRES_USER=idtnpr -e POSTGRES_PASSWORD=idtnpr -p 5432:5432 -d postgres:16
   ```
4. **Chaves RSA** geradas em `src/main/resources/certs/` — ver `certs/README.md`.
5. **Arquivo `.env`** com as variáveis locais. Copie o modelo:
   ```powershell
   Copy-Item .env.example .env   # (Linux/Mac: cp .env.example .env)
   ```
   O `.env` é carregado automaticamente em dev (via `springboot4-dotenv`) e **não é versionado**.
   `DB_PASSWORD` e `ADMIN_SENHA` são obrigatórias.

## Como rodar

```bash
cd backend
mvn spring-boot:run
```

A aplicação sobe em `http://localhost:8080`. Na primeira execução:
- O **Flyway** cria o schema e insere o conteúdo inicial do site.
- O **DataSeeder** cria o usuário admin (e-mail/senha vêm de `app.admin.*` no `application.yml`
  ou de variáveis de ambiente `ADMIN_EMAIL` / `ADMIN_SENHA`).

### Swagger UI
`http://localhost:8080/swagger-ui.html`

## Autenticação (fluxo)

```bash
# 1) Login -> recebe o token
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@idtnpr.org.br","senha":"troque-esta-senha"}'

# 2) Usar o token em rotas /api/admin/**
curl http://localhost:8080/api/admin/protocolos \
  -H "Authorization: Bearer <TOKEN>"
```

## Principais endpoints

| Método | Rota | Acesso | Descrição |
|--------|------|--------|-----------|
| POST | `/api/auth/login` | público | Autentica e retorna JWT |
| POST | `/api/protocolos` | público | Abre uma solicitação |
| GET | `/api/protocolos/{numero}` | público | Acompanha solicitação |
| POST | `/api/protocolos/{numero}/anexos` | público | Anexa arquivo |
| GET | `/api/conteudo` | público | Textos + projetos da landing |
| POST | `/api/contato` | público | Envia mensagem (Fale Conosco) |
| GET | `/api/arquivos/{id}` | público | Baixa arquivo |
| GET/PATCH | `/api/admin/protocolos/**` | ADMIN | Gerencia protocolos |
| GET/PUT | `/api/admin/conteudo` | ADMIN | Edita textos do site |
| CRUD | `/api/admin/projetos/**` | ADMIN | Gerencia projetos |
| GET/PATCH | `/api/admin/mensagens/**` | ADMIN | Caixa de entrada do contato |
| POST | `/api/admin/arquivos` | ADMIN | Upload de imagens |

## Configuração por ambiente (12-factor)

Toda a configuração vem de **variáveis de ambiente** — nenhum segredo é versionado:

- **Dev:** as variáveis ficam no arquivo `.env` (copiado de `.env.example`), carregado
  automaticamente pelo `springboot4-dotenv`. O `.env` está no `.gitignore`.
- **Prod:** defina as variáveis no ambiente do servidor (ou secret manager). **Não** há
  defaults para `DB_PASSWORD` e `ADMIN_SENHA`, então a aplicação falha ao subir se elas
  faltarem — proteção contra senhas fracas/conhecidas em produção.

Variáveis disponíveis: `DB_URL`, `DB_USER`, `DB_PASSWORD`, `CORS_ORIGINS`, `JWT_EXP_MINUTES`,
`STORAGE_DIR`, `ADMIN_NOME`, `ADMIN_EMAIL`, `ADMIN_SENHA`, `SERVER_PORT`, `SPRING_PROFILES_ACTIVE`.

## Testes

```bash
mvn test
```

## Segurança

Modelo de segurança, tratamento de segredos (12-factor) e checklist de produção em
[`SECURITY.md`](SECURITY.md).

## Próximos passos

Ver [`TAREFAS.md`](TAREFAS.md) para o backlog de evolução do back-end.
