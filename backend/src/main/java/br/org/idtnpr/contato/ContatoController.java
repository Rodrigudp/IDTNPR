package br.org.idtnpr.contato;

import br.org.idtnpr.contato.dto.MensagemContatoRequest;
import br.org.idtnpr.contato.dto.MensagemContatoResponse;
import io.swagger.v3.oas.annotations.Operation;
import io.swagger.v3.oas.annotations.security.SecurityRequirement;
import io.swagger.v3.oas.annotations.tags.Tag;
import jakarta.validation.Valid;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.web.PageableDefault;
import org.springframework.http.HttpStatus;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PatchMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.ResponseStatus;
import org.springframework.web.bind.annotation.RestController;

/**
 * "Fale Conosco": envio público de mensagens e leitura pelo admin.
 */
@RestController
@Tag(name = "Contato (Fale Conosco)")
public class ContatoController {

    private final ContatoService contatoService;

    public ContatoController(ContatoService contatoService) {
        this.contatoService = contatoService;
    }

    @PostMapping("/api/contato")
    @ResponseStatus(HttpStatus.CREATED)
    @Operation(summary = "Envia uma mensagem de contato (público)")
    public MensagemContatoResponse enviar(@Valid @RequestBody MensagemContatoRequest req) {
        return contatoService.registrar(req);
    }

    @GetMapping("/api/admin/mensagens")
    @SecurityRequirement(name = "bearer-jwt")
    @Operation(summary = "Lista mensagens recebidas (admin)")
    public Page<MensagemContatoResponse> listar(@RequestParam(required = false) Boolean lida,
                                                @PageableDefault(size = 20) Pageable pageable) {
        return contatoService.listar(lida, pageable);
    }

    @PatchMapping("/api/admin/mensagens/{id}/lida")
    @SecurityRequirement(name = "bearer-jwt")
    @Operation(summary = "Marca uma mensagem como lida (admin)")
    public MensagemContatoResponse marcarLida(@PathVariable Long id) {
        return contatoService.marcarComoLida(id);
    }
}
