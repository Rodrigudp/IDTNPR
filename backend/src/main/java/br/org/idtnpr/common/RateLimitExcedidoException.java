package br.org.idtnpr.common;

/**
 * Lançada quando um cliente excede o limite de tentativas. Resulta em HTTP 429.
 */
public class RateLimitExcedidoException extends RuntimeException {

    public RateLimitExcedidoException(String mensagem) {
        super(mensagem);
    }
}
