package br.org.idtnpr.conteudo;

import br.org.idtnpr.common.RecursoNaoEncontradoException;
import br.org.idtnpr.conteudo.dto.ConteudoSiteDto;
import br.org.idtnpr.conteudo.dto.LandingResponse;
import br.org.idtnpr.conteudo.dto.ProjetoRequest;
import br.org.idtnpr.conteudo.dto.ProjetoResponse;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

/**
 * Regras de negócio do conteúdo editável do site (textos e projetos).
 */
@Service
public class ConteudoService {

    private final ConteudoSiteRepository conteudoRepository;
    private final ProjetoRepository projetoRepository;

    public ConteudoService(ConteudoSiteRepository conteudoRepository, ProjetoRepository projetoRepository) {
        this.conteudoRepository = conteudoRepository;
        this.projetoRepository = projetoRepository;
    }

    @Transactional(readOnly = true)
    public LandingResponse obterLanding() {
        ConteudoSiteDto conteudo = ConteudoSiteDto.de(carregarConteudo());
        List<ProjetoResponse> projetos = projetoRepository.findAllByOrderByOrdemAsc().stream()
                .map(ProjetoResponse::de)
                .toList();
        return new LandingResponse(conteudo, projetos);
    }

    @Transactional(readOnly = true)
    public ConteudoSiteDto obterConteudo() {
        return ConteudoSiteDto.de(carregarConteudo());
    }

    @Transactional
    public ConteudoSiteDto atualizarConteudo(ConteudoSiteDto dto) {
        ConteudoSite conteudo = carregarConteudo();
        dto.aplicarEm(conteudo);
        return ConteudoSiteDto.de(conteudoRepository.save(conteudo));
    }

    @Transactional(readOnly = true)
    public List<ProjetoResponse> listarProjetos() {
        return projetoRepository.findAllByOrderByOrdemAsc().stream().map(ProjetoResponse::de).toList();
    }

    @Transactional
    public ProjetoResponse criarProjeto(ProjetoRequest req) {
        Projeto projeto = new Projeto(req.titulo(), req.descricao(), req.imagemUrl(), req.link(), req.ordem());
        return ProjetoResponse.de(projetoRepository.save(projeto));
    }

    @Transactional
    public ProjetoResponse atualizarProjeto(Long id, ProjetoRequest req) {
        Projeto projeto = projetoRepository.findById(id)
                .orElseThrow(() -> new RecursoNaoEncontradoException("Projeto não encontrado: " + id));
        projeto.setTitulo(req.titulo());
        projeto.setDescricao(req.descricao());
        projeto.setImagemUrl(req.imagemUrl());
        projeto.setLink(req.link());
        projeto.setOrdem(req.ordem());
        return ProjetoResponse.de(projetoRepository.save(projeto));
    }

    @Transactional
    public void removerProjeto(Long id) {
        if (!projetoRepository.existsById(id)) {
            throw new RecursoNaoEncontradoException("Projeto não encontrado: " + id);
        }
        projetoRepository.deleteById(id);
    }

    private ConteudoSite carregarConteudo() {
        return conteudoRepository.findById(ConteudoSite.ID_FIXO)
                .orElseThrow(() -> new RecursoNaoEncontradoException("Conteúdo do site não inicializado."));
    }
}
