package br.org.idtnpr.config;

import io.swagger.v3.oas.annotations.OpenAPIDefinition;
import io.swagger.v3.oas.annotations.enums.SecuritySchemeType;
import io.swagger.v3.oas.annotations.info.Info;
import io.swagger.v3.oas.annotations.security.SecurityScheme;
import org.springframework.context.annotation.Configuration;

/**
 * Configuração da documentação OpenAPI/Swagger.
 *
 * <p>Declara o esquema de segurança "bearer-jwt" (referenciado nos controllers
 * administrativos via {@code @SecurityRequirement}), habilitando o botão
 * "Authorize" no Swagger UI para colar o token.</p>
 */
@Configuration
@OpenAPIDefinition(info = @Info(
        title = "IDTNPR API",
        version = "v1",
        description = "Back-end do site IDTNPR (protocolos, conteúdo, contato e autenticação)."
))
@SecurityScheme(
        name = "bearer-jwt",
        type = SecuritySchemeType.HTTP,
        scheme = "bearer",
        bearerFormat = "JWT"
)
public class OpenApiConfig {
}
