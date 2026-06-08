package br.org.idtnpr.protocolo;

/**
 * Tipos de solicitação que um cidadão pode abrir no Protocolo Digital.
 * (Substitui as opções genéricas "1, 2, 3, 4" do formulário atual.)
 */
public enum TipoSolicitacao {
    INFORMACAO,
    SOLICITACAO_SERVICO,
    RECLAMACAO,
    DENUNCIA,
    ELOGIO,
    OUTRO
}
