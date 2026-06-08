package br.org.idtnpr.protocolo;

import br.org.idtnpr.protocolo.dto.AtualizarStatusRequest;
import br.org.idtnpr.protocolo.dto.ProtocoloResponse;
import io.swagger.v3.oas.annotations.Operation;
import io.swagger.v3.oas.annotations.security.SecurityRequirement;
import io.swagger.v3.oas.annotations.tags.Tag;
import jakarta.validation.Valid;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.web.PageableDefault;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PatchMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

/**
 * Gestão de protocolos pelo painel administrativo (requer token ADMIN).
 */
@RestController
@RequestMapping("/api/admin/protocolos")
@Tag(name = "Protocolos (admin)")
@SecurityRequirement(name = "bearer-jwt")
public class ProtocoloAdminController {

    private final ProtocoloService protocoloService;

    public ProtocoloAdminController(ProtocoloService protocoloService) {
        this.protocoloService = protocoloService;
    }

    @GetMapping
    @Operation(summary = "Lista protocolos (com filtro opcional por status e paginação)")
    public Page<ProtocoloResponse> listar(@RequestParam(required = false) StatusProtocolo status,
                                          @PageableDefault(size = 20) Pageable pageable) {
        return protocoloService.listar(status, pageable).map(ProtocoloResponse::de);
    }

    @GetMapping("/{numero}")
    @Operation(summary = "Detalha um protocolo")
    public ProtocoloResponse detalhar(@PathVariable String numero) {
        return ProtocoloResponse.de(protocoloService.buscarPorNumero(numero));
    }

    @PatchMapping("/{numero}/status")
    @Operation(summary = "Atualiza o status de um protocolo")
    public ProtocoloResponse atualizarStatus(@PathVariable String numero,
                                             @Valid @RequestBody AtualizarStatusRequest request) {
        return ProtocoloResponse.de(protocoloService.atualizarStatus(numero, request.status()));
    }
}
