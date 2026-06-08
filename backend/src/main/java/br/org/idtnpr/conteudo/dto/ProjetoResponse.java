package br.org.idtnpr.conteudo.dto;

import br.org.idtnpr.conteudo.Projeto;

public record ProjetoResponse(Long id, String titulo, String descricao, String imagemUrl, String link, int ordem) {

    public static ProjetoResponse de(Projeto p) {
        return new ProjetoResponse(p.getId(), p.getTitulo(), p.getDescricao(), p.getImagemUrl(), p.getLink(), p.getOrdem());
    }
}
