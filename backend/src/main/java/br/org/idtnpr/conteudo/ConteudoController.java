package br.org.idtnpr.conteudo;

import br.org.idtnpr.conteudo.dto.LandingResponse;
import br.org.idtnpr.conteudo.dto.ProjetoResponse;
import io.swagger.v3.oas.annotations.Operation;
import io.swagger.v3.oas.annotations.tags.Tag;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RestController;

import java.util.List;

/**
 * Endpoints públicos consumidos pela landing page.
 */
@RestController
@Tag(name = "Conteúdo do site (público)")
public class ConteudoController {

    private final ConteudoService conteudoService;

    public ConteudoController(ConteudoService conteudoService) {
        this.conteudoService = conteudoService;
    }

    @GetMapping("/api/conteudo")
    @Operation(summary = "Retorna textos e projetos da landing page")
    public LandingResponse conteudo() {
        return conteudoService.obterLanding();
    }

    @GetMapping("/api/projetos")
    @Operation(summary = "Lista os projetos em destaque")
    public List<ProjetoResponse> projetos() {
        return conteudoService.listarProjetos();
    }
}
