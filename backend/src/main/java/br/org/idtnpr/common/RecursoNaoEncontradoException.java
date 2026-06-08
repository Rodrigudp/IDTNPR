package br.org.idtnpr.common;

/**
 * Lançada quando um recurso solicitado não existe. Resulta em HTTP 404.
 */
public class RecursoNaoEncontradoException extends RuntimeException {

    public RecursoNaoEncontradoException(String mensagem) {
        super(mensagem);
    }
}
