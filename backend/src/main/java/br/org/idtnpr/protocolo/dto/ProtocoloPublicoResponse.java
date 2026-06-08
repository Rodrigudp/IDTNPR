package br.org.idtnpr.protocolo.dto;

import br.org.idtnpr.protocolo.Protocolo;
import br.org.idtnpr.protocolo.StatusProtocolo;
import br.org.idtnpr.protocolo.TipoSolicitacao;

import java.time.LocalDateTime;
import java.util.List;

/**
 * Versão pública (acompanhamento) de um protocolo, com o <b>CPF mascarado</b>.
 *
 * <p>O número do protocolo funciona como capability de acesso; se ele vazar, esta
 * resposta limita a exposição de dado pessoal sensível (LGPD). O CPF completo só
 * é retornado nos endpoints administrativos ({@link ProtocoloResponse}).</p>
 */
public record ProtocoloPublicoResponse(
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

    public static ProtocoloPublicoResponse de(Protocolo p) {
        return new ProtocoloPublicoResponse(
                p.getNumero(),
                p.getNome(),
                mascararCpf(p.getCpf()),
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

    /** Mantém apenas os 2 últimos dígitos do CPF: "***.***.***-12". */
    private static String mascararCpf(String cpf) {
        if (cpf == null || cpf.length() < 2) {
            return "***.***.***-**";
        }
        return "***.***.***-" + cpf.substring(cpf.length() - 2);
    }
}
