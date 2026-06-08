package br.org.idtnpr.protocolo.dto;

import br.org.idtnpr.protocolo.TipoSolicitacao;
import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.Size;

/**
 * Dados enviados para abrir uma nova solicitação (público).
 */
public record CriarProtocoloRequest(
        @NotBlank @Size(max = 150) String nome,
        @NotBlank @Size(min = 11, max = 14) String cpf,
        @NotBlank @Email String email,
        @Size(max = 20) String telefone,
        @NotNull TipoSolicitacao tipoSolicitacao,
        @NotBlank @Size(max = 5000) String descricao
) {
}
