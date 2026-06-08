package br.org.idtnpr.config;

import org.springframework.boot.context.properties.ConfigurationProperties;

import java.util.List;

/**
 * Configurações de aplicação (prefixo {@code app} no application.yml).
 *
 * @param corsAllowedOrigins origens liberadas no CORS (frontend)
 * @param jwtExpirationMinutes validade do token de acesso, em minutos
 * @param storageDir diretório onde os uploads são gravados
 * @param admin credenciais do admin inicial criado pelo DataSeeder
 */
@ConfigurationProperties(prefix = "app")
public record AppProperties(
        List<String> corsAllowedOrigins,
        long jwtExpirationMinutes,
        String storageDir,
        Admin admin
) {
    public record Admin(String nome, String email, String senha) {
    }
}
