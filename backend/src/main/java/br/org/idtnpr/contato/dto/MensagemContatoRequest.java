package br.org.idtnpr.contato.dto;

import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.Size;

public record MensagemContatoRequest(
        @NotBlank @Size(max = 150) String nome,
        @NotBlank @Email String email,
        @Size(max = 20) String telefone,
        @NotBlank @Size(max = 5000) String mensagem
) {
}
