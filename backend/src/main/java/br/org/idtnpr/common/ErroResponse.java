package br.org.idtnpr.common;

import java.time.LocalDateTime;
import java.util.List;

/**
 * Corpo padrão das respostas de erro da API.
 *
 * @param timestamp momento do erro
 * @param status    código HTTP
 * @param erro      descrição curta do status (ex.: "Bad Request")
 * @param mensagem  mensagem amigável
 * @param campos    erros de validação por campo (quando houver)
 */
public record ErroResponse(
        LocalDateTime timestamp,
        int status,
        String erro,
        String mensagem,
        List<CampoComErro> campos
) {

    public record CampoComErro(String campo, String mensagem) {
    }

    public static ErroResponse de(int status, String erro, String mensagem) {
        return new ErroResponse(LocalDateTime.now(), status, erro, mensagem, List.of());
    }

    public static ErroResponse de(int status, String erro, String mensagem, List<CampoComErro> campos) {
        return new ErroResponse(LocalDateTime.now(), status, erro, mensagem, campos);
    }
}
