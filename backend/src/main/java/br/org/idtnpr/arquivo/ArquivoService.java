package br.org.idtnpr.arquivo;

import br.org.idtnpr.arquivo.dto.ArquivoResponse;
import br.org.idtnpr.common.RecursoNaoEncontradoException;
import br.org.idtnpr.common.RegraNegocioException;
import org.springframework.core.io.Resource;
import org.springframework.core.io.UrlResource;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import org.springframework.web.multipart.MultipartFile;

import java.io.IOException;
import java.nio.file.Path;

/**
 * Orquestra a gravação física (ArmazenamentoService) e os metadados (Arquivo) dos uploads.
 */
@Service
public class ArquivoService {

    private final ArquivoRepository arquivoRepository;
    private final ArmazenamentoService armazenamentoService;

    public ArquivoService(ArquivoRepository arquivoRepository, ArmazenamentoService armazenamentoService) {
        this.arquivoRepository = arquivoRepository;
        this.armazenamentoService = armazenamentoService;
    }

    @Transactional
    public ArquivoResponse enviar(MultipartFile arquivo) {
        String caminho = armazenamentoService.salvar(arquivo, "site");
        Arquivo entidade = new Arquivo(
                arquivo.getOriginalFilename(),
                arquivo.getContentType(),
                arquivo.getSize(),
                caminho
        );
        return ArquivoResponse.de(arquivoRepository.save(entidade));
    }

    @Transactional(readOnly = true)
    public Arquivo buscar(Long id) {
        return arquivoRepository.findById(id)
                .orElseThrow(() -> new RecursoNaoEncontradoException("Arquivo não encontrado: " + id));
    }

    public Resource carregarConteudo(Arquivo arquivo) {
        try {
            Path caminho = armazenamentoService.resolver(arquivo.getCaminho());
            Resource resource = new UrlResource(caminho.toUri());
            if (!resource.exists() || !resource.isReadable()) {
                throw new RecursoNaoEncontradoException("Arquivo físico ausente: " + arquivo.getCaminho());
            }
            return resource;
        } catch (IOException e) {
            throw new RegraNegocioException("Falha ao ler o arquivo: " + e.getMessage());
        }
    }
}
