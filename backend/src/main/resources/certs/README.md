# Chaves RSA para assinatura dos JWT

Os arquivos `public.pem` e `private.pem` **não são versionados** (ver `.gitignore`).
Gere-os localmente antes de subir a aplicação.

## Gerar o par de chaves (precisa do OpenSSL)

```bash
# A partir desta pasta (backend/src/main/resources/certs):

# 1) Chave privada
openssl genrsa -out keypair.pem 2048

# 2) Chave pública (derivada da privada)
openssl rsa -in keypair.pem -pubout -out public.pem

# 3) Chave privada no formato PKCS#8 (exigido pelo Java)
openssl pkcs8 -topk8 -inform PEM -outform PEM -nocrypt -in keypair.pem -out private.pem

# 4) Remover o arquivo intermediário
rm keypair.pem
```

No Windows (PowerShell), use os mesmos comandos com o OpenSSL instalado
(ex.: via Git for Windows, normalmente em `C:\Program Files\Git\usr\bin\openssl.exe`),
ou use o WSL.

Em **produção**, aponte `rsa.public-key` / `rsa.private-key` para um local seguro
(ex.: variáveis de ambiente ou um secret manager) em vez de arquivos no classpath.
