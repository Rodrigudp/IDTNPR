# Segurança — Back-end IDTNPR

Este documento descreve o modelo de segurança da API: como os segredos são tratados,
quais proteções já estão implementadas, e o checklist para um deploy seguro em produção.

---

## 1. Configuração e segredos (12-factor)

Toda configuração sensível vem de **variáveis de ambiente** — **nenhum segredo é versionado** no Git.

| Ambiente | Origem das variáveis | Versionado? |
|----------|----------------------|-------------|
| Desenvolvimento | arquivo `.env` (carregado pelo `springboot4-dotenv`) | ❌ `.env` está no `.gitignore` |
| Produção | ambiente do servidor / secret manager | ❌ nenhum segredo no repositório |
| Molde de referência | `.env.example` (valores fictícios) | ✅ versionado |

A aplicação lê apenas placeholders (`${DB_PASSWORD}`, `${ADMIN_SENHA}`, ...) — ela não sabe
se o valor veio de um `.env` ou do ambiente real. Mesmo código em dev e prod.

**Fail-fast:** `DB_PASSWORD` e `ADMIN_SENHA` **não têm default**. Se faltarem em produção, a
aplicação **não sobe** — evitando que um servidor entre no ar com senha fraca/conhecida.

### Arquivos que NUNCA devem ser commitados
- `.env` (segredos locais)
- `src/main/resources/certs/*.pem` (chaves RSA)
- pasta `uploads/` (arquivos enviados em runtime)

Todos já estão no [`.gitignore`](.gitignore).

### Chaves RSA (assinatura dos JWT)
Geradas localmente, fora do Git. Veja [`src/main/resources/certs/README.md`](src/main/resources/certs/README.md).
Em produção, aponte `rsa.public-key` / `rsa.private-key` para um local seguro.

---

## 2. Proteções implementadas

### Autenticação e autorização
- **JWT stateless** assinado com **RSA** (OAuth2 Resource Server); rotas `/api/admin/**` exigem papel `ADMIN`.
- **Senhas com BCrypt** (nunca em texto puro).
- **Rate limiting no login** — 5 tentativas / 15 min por IP → `429 Too Many Requests` (anti força-bruta).
- **Mensagem de erro genérica** no login ("Credenciais inválidas") — sem revelar se o e-mail existe.

### Dados pessoais (LGPD)
- **Número de protocolo imprevisível** (`SecureRandom`, ~100 bits) — funciona como capability, impedindo
  enumeração (IDOR) das solicitações.
- **CPF mascarado** na resposta pública de acompanhamento (`***.***.***-12`); CPF completo só em `/api/admin/**`.

### Upload de arquivos
- **Anexos de protocolo:** allow-list `PDF, JPG, PNG`.
- **Imagens do site (admin):** allow-list `PNG, JPG, WEBP, GIF` — **SVG bloqueado** (evita XSS armazenado).
- **Nomes físicos via UUID** e validação de caminho (`startsWith(raiz)`) — sem path traversal.
- Limites de tamanho de upload (10 MB por arquivo / 30 MB por requisição).

### Plataforma
- **Cabeçalhos de segurança** automáticos do Spring Security (`X-Content-Type-Options: nosniff`,
  `X-Frame-Options: DENY`).
- **CORS restrito** a origens configuradas (nunca `*` com credenciais).
- **CSRF desabilitado** corretamente (API stateless por token, sem cookies de sessão).
- **Swagger/OpenAPI desligado em produção** (`application-prod.yml`).
- **Sem SQL injection:** apenas Spring Data JPA (queries parametrizadas).
- **Sem mass assignment:** entradas via DTOs (`record`) explícitos, nunca ligando JSON direto na entidade.

---

## 3. Checklist de deploy em produção

- [ ] Definir `SPRING_PROFILES_ACTIVE=prod`.
- [ ] Definir `DB_PASSWORD` e `ADMIN_SENHA` (fortes) via ambiente — **nunca** em arquivo versionado.
- [ ] Trocar a senha do admin após o primeiro login.
- [ ] Servir a API **somente via HTTPS** (TLS no proxy/load balancer).
- [ ] Configurar `CORS_ORIGINS` apenas com o domínio real do frontend.
- [ ] Gerar um par de chaves RSA **exclusivo de produção** (não reutilizar o de dev).
- [ ] Garantir que `springdoc`/Swagger está desligado (já vem desligado no perfil `prod`).
- [ ] Se houver proxy reverso, configurar `server.forward-headers-strategy` para o rate limiting
      enxergar o IP real do cliente (`X-Forwarded-For`).
- [ ] Apontar o storage de arquivos para um volume persistente ou bucket (S3/MinIO).

---

## 4. Itens conhecidos a evoluir

Registrados no [`TAREFAS.md`](TAREFAS.md), seção "Segurança e autenticação":
- Refresh token + recuperação de senha.
- Rate limiting também em `/api/protocolos` e `/api/contato`; mover o limiter para Caffeine/Redis.
- Validar o conteúdo real dos uploads por *magic bytes* (não só o `content-type` declarado).
- Auditoria (quem alterou o quê) e bloqueio de conta após N falhas.

---

## 5. Como reportar uma vulnerabilidade

Por se tratar de um projeto acadêmico, reporte falhas de segurança diretamente à equipe do projeto
(contato no `README.md`), e não em issues públicas.
