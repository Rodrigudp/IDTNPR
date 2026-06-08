package br.org.idtnpr.protocolo;

import java.util.Set;

/**
 * Estados possíveis de um protocolo ao longo do seu ciclo de vida.
 */
public enum StatusProtocolo {
    ABERTO,
    EM_ANALISE,
    CONCLUIDO,
    ARQUIVADO;

    /**
     * Transições permitidas a partir de cada status.
     *
     * <p>TODO (você): esta é uma decisão de regra de negócio que vale a pena você
     * desenhar. O mapa abaixo é um ponto de partida razoável; ajuste conforme o
     * fluxo real do IDTNPR. Por exemplo: um protocolo CONCLUIDO pode ser reaberto?
     * Um ARQUIVADO é estado final de verdade?</p>
     */
    public Set<StatusProtocolo> transicoesPermitidas() {
        return switch (this) {
            case ABERTO -> Set.of(EM_ANALISE, ARQUIVADO);
            case EM_ANALISE -> Set.of(CONCLUIDO, ARQUIVADO);
            case CONCLUIDO -> Set.of(ARQUIVADO);
            case ARQUIVADO -> Set.of();
        };
    }

    public boolean podeTransicionarPara(StatusProtocolo destino) {
        return transicoesPermitidas().contains(destino);
    }
}
