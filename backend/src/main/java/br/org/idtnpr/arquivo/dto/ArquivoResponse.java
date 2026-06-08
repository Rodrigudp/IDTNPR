package br.org.idtnpr.arquivo.dto;

import br.org.idtnpr.arquivo.Arquivo;

/**
 * Metadados + URL pública de download de um arquivo enviado.
 */
public record ArquivoResponse(Long id, String nomeOriginal, String contentType, Long tamanhoBytes, String url) {

    public static ArquivoResponse de(Arquivo a) {
        return new ArquivoResponse(
                a.getId(), a.getNomeOriginal(), a.getContentType(), a.getTamanhoBytes(),
                "/api/arquivos/" + a.getId());
    }
}
