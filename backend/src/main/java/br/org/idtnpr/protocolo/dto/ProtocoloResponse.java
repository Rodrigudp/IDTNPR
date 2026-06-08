package br.org.idtnpr.protocolo.dto;

import br.org.idtnpr.protocolo.Protocolo;
import br.org.idtnpr.protocolo.StatusProtocolo;
import br.org.idtnpr.protocolo.TipoSolicitacao;

import java.time.LocalDateTime;
import java.util.List;

/**
 * Representação de um protocolo retornada pela API.
 */
public record ProtocoloResponse(
        String numero,
        String nome,
        String cpf,
        String email,
        String telefone,
        TipoSolicitacao tipoSolicitacao,
        String descricao,
        StatusProtocolo status,
        LocalDateTime criadoEm,
        LocalDateTime atualizadoEm,
        List<AnexoResponse> anexos
) {

    public static ProtocoloResponse de(Protocolo p) {
        return new ProtocoloResponse(
                p.getNumero(),
                p.getNome(),
                p.getCpf(),
                p.getEmail(),
                p.getTelefone(),
                p.getTipoSolicitacao(),
                p.getDescricao(),
                p.getStatus(),
                p.getCriadoEm(),
                p.getAtualizadoEm(),
                p.getAnexos().stream().map(AnexoResponse::de).toList()
        );
    }
}
