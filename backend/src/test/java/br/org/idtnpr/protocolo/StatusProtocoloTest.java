package br.org.idtnpr.protocolo;

import org.junit.jupiter.api.Test;

import static org.assertj.core.api.Assertions.assertThat;

/**
 * Verifica as regras de transição de status (regra de negócio pura, sem Spring).
 */
class StatusProtocoloTest {

    @Test
    void abertoPodeIrParaEmAnaliseEArquivado() {
        assertThat(StatusProtocolo.ABERTO.podeTransicionarPara(StatusProtocolo.EM_ANALISE)).isTrue();
        assertThat(StatusProtocolo.ABERTO.podeTransicionarPara(StatusProtocolo.ARQUIVADO)).isTrue();
    }

    @Test
    void abertoNaoPodeIrDiretoParaConcluido() {
        assertThat(StatusProtocolo.ABERTO.podeTransicionarPara(StatusProtocolo.CONCLUIDO)).isFalse();
    }

    @Test
    void arquivadoEhEstadoFinal() {
        assertThat(StatusProtocolo.ARQUIVADO.transicoesPermitidas()).isEmpty();
    }

    @Test
    void emAnalisePodeConcluir() {
        assertThat(StatusProtocolo.EM_ANALISE.podeTransicionarPara(StatusProtocolo.CONCLUIDO)).isTrue();
    }
}
