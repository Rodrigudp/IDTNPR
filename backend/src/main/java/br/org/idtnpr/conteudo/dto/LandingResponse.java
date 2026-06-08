package br.org.idtnpr.conteudo.dto;

import java.util.List;

/**
 * Tudo que a landing page precisa em uma única chamada (substitui o localStorage).
 */
public record LandingResponse(ConteudoSiteDto conteudo, List<ProjetoResponse> projetos) {
}
