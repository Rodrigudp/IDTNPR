package br.org.idtnpr.protocolo.dto;

import br.org.idtnpr.protocolo.StatusProtocolo;
import jakarta.validation.constraints.NotNull;

/**
 * Novo status enviado pelo admin ao gerenciar um protocolo.
 */
public record AtualizarStatusRequest(@NotNull StatusProtocolo status) {
}
