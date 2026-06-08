package br.org.idtnpr.arquivo;

import br.org.idtnpr.arquivo.dto.ArquivoResponse;
import io.swagger.v3.oas.annotations.Operation;
import io.swagger.v3.oas.annotations.security.SecurityRequirement;
import io.swagger.v3.oas.annotations.tags.Tag;
import org.springframework.core.io.Resource;
import org.springframework.http.HttpHeaders;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.ResponseStatus;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.multipart.MultipartFile;

/**
 * Upload (admin) e download (público) de arquivos do site.
 */
@RestController
@Tag(name = "Arquivos")
public class ArquivoController {

    private final ArquivoService arquivoService;

    public ArquivoController(ArquivoService arquivoService) {
        this.arquivoService = arquivoService;
    }

    @PostMapping(value = "/api/admin/arquivos", consumes = MediaType.MULTIPART_FORM_DATA_VALUE)
    @ResponseStatus(HttpStatus.CREATED)
    @SecurityRequirement(name = "bearer-jwt")
    @Operation(summary = "Envia um arquivo (logo, imagem hero, etc.) e retorna sua URL")
    public ArquivoResponse upload(@RequestParam("arquivo") MultipartFile arquivo) {
        return arquivoService.enviar(arquivo);
    }

    @GetMapping("/api/arquivos/{id}")
    @Operation(summary = "Baixa um arquivo pelo id (público)")
    public ResponseEntity<Resource> download(@PathVariable Long id) {
        Arquivo arquivo = arquivoService.buscar(id);
        Resource resource = arquivoService.carregarConteudo(arquivo);

        String contentType = arquivo.getContentType() != null
                ? arquivo.getContentType()
                : MediaType.APPLICATION_OCTET_STREAM_VALUE;

        return ResponseEntity.ok()
                .contentType(MediaType.parseMediaType(contentType))
                .header(HttpHeaders.CONTENT_DISPOSITION, "inline; filename=\"" + arquivo.getNomeOriginal() + "\"")
                .body(resource);
    }
}
