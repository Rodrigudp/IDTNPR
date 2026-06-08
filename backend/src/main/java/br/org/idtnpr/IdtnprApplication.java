package br.org.idtnpr;

import br.org.idtnpr.config.AppProperties;
import br.org.idtnpr.config.RsaKeyProperties;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.context.properties.EnableConfigurationProperties;

/**
 * Ponto de entrada da aplicação.
 *
 * <p>{@code @EnableConfigurationProperties} habilita o binding das chaves RSA
 * (usadas para assinar/validar os tokens JWT) e das configurações da aplicação
 * a partir do application.yml.</p>
 */
@SpringBootApplication
@EnableConfigurationProperties({RsaKeyProperties.class, AppProperties.class})
public class IdtnprApplication {

    public static void main(String[] args) {
        SpringApplication.run(IdtnprApplication.class, args);
    }
}
