package br.org.idtnpr.auth.dto;

import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;

/**
 * Credenciais enviadas no login.
 */
public record LoginRequest(
        @NotBlank @Email String email,
        @NotBlank String senha
) {
}
