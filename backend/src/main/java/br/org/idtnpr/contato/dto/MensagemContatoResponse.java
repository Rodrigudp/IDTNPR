package br.org.idtnpr.contato.dto;

import br.org.idtnpr.contato.MensagemContato;

import java.time.LocalDateTime;

public record MensagemContatoResponse(
        Long id,
        String nome,
        String email,
        String telefone,
        String mensagem,
        boolean lida,
        LocalDateTime criadoEm
) {

    public static MensagemContatoResponse de(MensagemContato m) {
        return new MensagemContatoResponse(
                m.getId(), m.getNome(), m.getEmail(), m.getTelefone(),
                m.getMensagem(), m.isLida(), m.getCriadoEm());
    }
}
