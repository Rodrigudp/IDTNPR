package br.org.idtnpr.protocolo;

import br.org.idtnpr.protocolo.dto.CriarProtocoloRequest;
import br.org.idtnpr.protocolo.dto.ProtocoloResponse;
import io.swagger.v3.oas.annotations.Operation;
import io.swagger.v3.oas.annotations.tags.Tag;
import jakarta.validation.Valid;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.util.UriComponentsBuilder;

import java.net.URI;

/**
 * Endpoints públicos do Protocolo Digital (abrir, acompanhar e anexar).
 */
@RestController
@RequestMapping("/api/protocolos")
@Tag(name = "Protocolos (público)")
public class ProtocoloController {

    private final ProtocoloService protocoloService;

    public ProtocoloController(ProtocoloService protocoloService) {
        this.protocoloService = protocoloService;
    }

    @PostMapping
    @Operation(summary = "Abre uma nova solicitação e retorna o número de protocolo")
    public ResponseEntity<ProtocoloResponse> abrir(@Valid @RequestBody CriarProtocoloRequest request,
                                                   UriComponentsBuilder uriBuilder) {
        Protocolo protocolo = protocoloService.abrir(request);
        URI location = uriBuilder.path("/api/protocolos/{numero}")
                .buildAndExpand(protocolo.getNumero()).toUri();
        return ResponseEntity.created(location).body(ProtocoloResponse.de(protocolo));
    }

    @GetMapping("/{numero}")
    @Operation(summary = "Consulta uma solicitação pelo número de protocolo")
    public ResponseEntity<ProtocoloResponse> acompanhar(@PathVariable String numero) {
        return ResponseEntity.ok(ProtocoloResponse.de(protocoloService.buscarPorNumero(numero)));
    }

    @PostMapping("/{numero}/anexos")
    @Operation(summary = "Anexa um arquivo a uma solicitação existente")
    public ResponseEntity<ProtocoloResponse> anexar(@PathVariable String numero,
                                                    @RequestParam("arquivo") org.springframework.web.multipart.MultipartFile arquivo) {
        Protocolo protocolo = protocoloService.anexar(numero, arquivo);
        return ResponseEntity.ok(ProtocoloResponse.de(protocolo));
    }
}
