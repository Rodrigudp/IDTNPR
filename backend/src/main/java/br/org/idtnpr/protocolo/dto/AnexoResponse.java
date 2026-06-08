package br.org.idtnpr.protocolo.dto;

import br.org.idtnpr.protocolo.Anexo;

public record AnexoResponse(Long id, String nomeOriginal, String contentType, Long tamanhoBytes) {

    public static AnexoResponse de(Anexo anexo) {
        return new AnexoResponse(anexo.getId(), anexo.getNomeOriginal(), anexo.getContentType(), anexo.getTamanhoBytes());
    }
}
