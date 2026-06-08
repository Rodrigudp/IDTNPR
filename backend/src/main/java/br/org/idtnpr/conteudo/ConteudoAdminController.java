package br.org.idtnpr.conteudo;

import br.org.idtnpr.conteudo.dto.ConteudoSiteDto;
import br.org.idtnpr.conteudo.dto.ProjetoRequest;
import br.org.idtnpr.conteudo.dto.ProjetoResponse;
import io.swagger.v3.oas.annotations.Operation;
import io.swagger.v3.oas.annotations.security.SecurityRequirement;
import io.swagger.v3.oas.annotations.tags.Tag;
import jakarta.validation.Valid;
import org.springframework.http.HttpStatus;
import org.springframework.web.bind.annotation.DeleteMapping;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.PutMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.ResponseStatus;
import org.springframework.web.bind.annotation.RestController;

import java.util.List;

/**
 * Edição do conteúdo do site pelo painel administrativo (requer token ADMIN).
 */
@RestController
@RequestMapping("/api/admin")
@Tag(name = "Conteúdo do site (admin)")
@SecurityRequirement(name = "bearer-jwt")
public class ConteudoAdminController {

    private final ConteudoService conteudoService;

    public ConteudoAdminController(ConteudoService conteudoService) {
        this.conteudoService = conteudoService;
    }

    // ----- Textos / contato / imagens -----

    @GetMapping("/conteudo")
    @Operation(summary = "Obtém o conteúdo textual atual")
    public ConteudoSiteDto obter() {
        return conteudoService.obterConteudo();
    }

    @PutMapping("/conteudo")
    @Operation(summary = "Atualiza o conteúdo textual do site")
    public ConteudoSiteDto atualizar(@RequestBody ConteudoSiteDto dto) {
        return conteudoService.atualizarConteudo(dto);
    }

    // ----- Projetos -----

    @GetMapping("/projetos")
    @Operation(summary = "Lista projetos (admin)")
    public List<ProjetoResponse> listarProjetos() {
        return conteudoService.listarProjetos();
    }

    @PostMapping("/projetos")
    @ResponseStatus(HttpStatus.CREATED)
    @Operation(summary = "Cria um projeto")
    public ProjetoResponse criarProjeto(@Valid @RequestBody ProjetoRequest req) {
        return conteudoService.criarProjeto(req);
    }

    @PutMapping("/projetos/{id}")
    @Operation(summary = "Atualiza um projeto")
    public ProjetoResponse atualizarProjeto(@PathVariable Long id, @Valid @RequestBody ProjetoRequest req) {
        return conteudoService.atualizarProjeto(id, req);
    }

    @DeleteMapping("/projetos/{id}")
    @ResponseStatus(HttpStatus.NO_CONTENT)
    @Operation(summary = "Remove um projeto")
    public void removerProjeto(@PathVariable Long id) {
        conteudoService.removerProjeto(id);
    }
}
