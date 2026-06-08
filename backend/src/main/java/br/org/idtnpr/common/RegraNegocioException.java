package br.org.idtnpr.common;

/**
 * Lançada quando uma regra de negócio é violada. Resulta em HTTP 400.
 */
public class RegraNegocioException extends RuntimeException {

    public RegraNegocioException(String mensagem) {
        super(mensagem);
    }
}
