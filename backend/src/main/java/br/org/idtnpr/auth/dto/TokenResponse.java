package br.org.idtnpr.auth.dto;

/**
 * Resposta do login: o token de acesso e seus metadados.
 *
 * @param accessToken JWT a ser enviado no header {@code Authorization: Bearer <token>}
 * @param tokenType   sempre "Bearer"
 * @param expiresIn   validade do token em segundos
 */
public record TokenResponse(String accessToken, String tokenType, long expiresIn) {

    public static TokenResponse bearer(String accessToken, long expiresInSeconds) {
        return new TokenResponse(accessToken, "Bearer", expiresInSeconds);
    }
}
