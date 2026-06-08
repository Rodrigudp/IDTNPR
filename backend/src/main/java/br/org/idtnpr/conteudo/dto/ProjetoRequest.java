package br.org.idtnpr.conteudo.dto;

import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.Size;

public record ProjetoRequest(
        @NotBlank @Size(max = 150) String titulo,
        String descricao,
        @Size(max = 500) String imagemUrl,
        @Size(max = 500) String link,
        int ordem
) {
}
