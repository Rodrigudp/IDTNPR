# Lista de Tarefas — Evolução do Back-end IDTNPR

Backlog organizado por prioridade. A base atual já entrega: autenticação JWT, protocolos,
conteúdo do site, contato e upload de arquivos. As tarefas abaixo evoluem essa fundação.

> Legenda: 🔴 essencial · 🟡 importante · 🟢 desejável

---

## 0. Pontos de decisão deixados no código (resolver primeiro)

- [ ] 🟡 **Número de protocolo** — já é gerado de forma **imprevisível** (`SecureRandom`, ~100 bits)
      para evitar IDOR, pois funciona como capability de acesso. Opcional: emitir também um
      número sequencial legível **separado**, só para uso interno/admin (sem expô-lo no lookup público).
- [ ] 🔴 **Transições de status** — revisar `StatusProtocolo.transicoesPermitidas()` conforme
      o fluxo real (um protocolo concluído pode ser reaberto? quem pode arquivar?).

## 1. Segurança e autenticação

- [x] 🔴 ~~Defaults fracos de senha (DB/admin) no config~~ — removidos; em produção `DB_PASSWORD`/`ADMIN_SENHA` são obrigatórios (a app falha ao subir sem eles).
- [x] 🟡 ~~Swagger/api-docs exposto em produção~~ — desativado no perfil `prod`.
- [x] 🟡 ~~Upload de admin aceitava qualquer tipo (risco de XSS via SVG)~~ — restrito a imagens raster (PNG/JPG/WEBP/GIF).
- [ ] 🔴 Implementar **refresh token** (token de acesso curto + refresh longo).
- [ ] 🟡 **Recuperação de senha** (gerar token por e-mail, endpoint de redefinição).
- [ ] 🟡 CRUD de **usuários do admin** (criar/editar/desativar) — rota `/api/admin/usuarios`.
- [ ] 🟡 Diferenciar permissões entre `ADMIN` e `EDITOR` (usar `@PreAuthorize` por método).
- [x] 🔴 ~~Bloqueio após N tentativas de login~~ — feito (`LoginAttemptService`, 5 tentativas/15min por IP, retorna 429).
- [ ] 🟡 **Rate limiting** também nos demais públicos (`/api/protocolos`, `/api/contato`) contra spam.
- [ ] 🟡 Mover o rate limit para Caffeine/Redis (hoje é em memória, vale para 1 instância) e tratar `X-Forwarded-For` atrás de proxy.
- [ ] 🟢 Auditoria: registrar quem alterou status/conteúdo (campos `criado_por`/`atualizado_por`).

## 2. Domínio Protocolo

- [ ] 🔴 **Notificação por e-mail** ao abrir protocolo (enviar número) e a cada mudança de status.
- [ ] 🟡 **Histórico de tramitação** (entidade `MovimentacaoProtocolo`: status anterior, novo, autor, data, observação).
- [ ] 🟡 Busca/filtro avançado de protocolos (por CPF, período, tipo, texto na descrição).
- [ ] 🟡 Validação de **CPF** (dígitos verificadores) — criar `@CpfValido`.
- [x] 🟡 ~~Mascarar PII na resposta pública~~ — feito (`ProtocoloPublicoResponse` mascara o CPF; CPF completo só em `/api/admin/**`).
- [ ] 🟡 Validar o **conteúdo real** do anexo/imagem (magic bytes), não só o `content-type` declarado pelo cliente.
- [ ] 🟢 Exportar protocolos para CSV/PDF (relatórios para a gestão).
- [ ] 🟢 Limitar tamanho de anexos por configuração e quantidade máxima por protocolo.

## 3. Conteúdo do site

- [ ] 🟡 Versionamento/rascunho de conteúdo (publicar vs. rascunho) — reproduz o botão "Publicar" do admin.
- [ ] 🟢 Histórico de alterações de textos (para reverter).
- [ ] 🟢 Suporte a múltiplas seções dinâmicas (hoje os campos são fixos).

## 4. Área de Transparência (presente no site real)

- [ ] 🟡 Entidade `DocumentoTransparencia` (título, categoria, arquivo, data de publicação).
- [ ] 🟡 Endpoints: listagem pública + CRUD admin.
- [ ] 🟢 Categorias e busca por documento.

## 5. Qualidade, testes e observabilidade

- [ ] 🔴 Testes de integração dos controllers com **MockMvc** (auth, protocolo, conteúdo).
- [ ] 🟡 Testes com **Testcontainers** (PostgreSQL real) para repositórios e migrations.
- [ ] 🟡 Padronizar paginação: migrar respostas `Page<>` para `PagedModel` (evita o warning de serialização).
- [ ] 🟡 **Spring Boot Actuator** (`/actuator/health`, métricas).
- [ ] 🟢 Cobertura de testes com JaCoCo + meta mínima no build.
- [ ] 🟢 Logs estruturados (JSON) e correlação de requisições.

## 6. Infraestrutura e deploy

- [ ] 🔴 **Dockerfile** + `docker-compose.yml` (app + PostgreSQL).
- [ ] 🟡 **CI** (GitHub Actions): `mvn verify` em cada push/PR.
- [ ] 🟡 Mover armazenamento de arquivos do disco local para um bucket (S3/MinIO) em produção.
- [ ] 🟡 Externalizar as chaves RSA e segredos (secret manager / variáveis de ambiente).
- [ ] 🟢 Perfis de staging/produção e healthchecks no orquestrador.

## 7. Integração com o frontend (próxima grande fase)

- [ ] 🔴 Trocar o `localStorage` do `admin.html` por chamadas reais à API (`/api/admin/**`).
- [ ] 🔴 Tela de **login** no admin consumindo `/api/auth/login` e guardando o JWT.
- [ ] 🔴 Ligar o formulário de `protocolo.html` ao `POST /api/protocolos` + tela de acompanhamento.
- [ ] 🟡 Landing (`index.html`) carregar textos/projetos de `GET /api/conteudo`.
- [ ] 🟡 Ligar o "Fale Conosco" ao `POST /api/contato`.
