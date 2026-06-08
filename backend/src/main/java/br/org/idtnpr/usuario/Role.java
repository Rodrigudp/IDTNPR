package br.org.idtnpr.usuario;

/**
 * Papéis de acesso ao painel administrativo.
 *
 * <ul>
 *   <li>{@code ADMIN} — acesso total.</li>
 *   <li>{@code EDITOR} — pode editar conteúdo, mas com menos privilégios (reservado para evolução).</li>
 * </ul>
 */
public enum Role {
    ADMIN,
    EDITOR
}
