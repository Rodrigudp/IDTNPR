package br.org.idtnpr.config;

import org.springframework.boot.context.properties.ConfigurationProperties;

import java.security.interfaces.RSAPrivateKey;
import java.security.interfaces.RSAPublicKey;

/**
 * Liga as chaves RSA configuradas no application.yml (prefixo {@code rsa}).
 *
 * <p>O Spring Boot converte automaticamente os arquivos PEM apontados em
 * {@code rsa.public-key} / {@code rsa.private-key} para os tipos
 * {@link RSAPublicKey} / {@link RSAPrivateKey}.</p>
 *
 * <ul>
 *   <li>{@code privateKey} — assina os tokens emitidos no login.</li>
 *   <li>{@code publicKey} — valida a assinatura dos tokens recebidos.</li>
 * </ul>
 */
@ConfigurationProperties(prefix = "rsa")
public record RsaKeyProperties(RSAPublicKey publicKey, RSAPrivateKey privateKey) {
}
