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
import java.util.Set;

/**
 * Orquestra a gravação física (ArmazenamentoService) e os metadados (Arquivo) dos uploads.
 */
@Service
public class ArquivoService {

    /**
     * Tipos aceitos no upload do site (logo/hero). Apenas imagens RASTER —
     * propositalmente sem SVG, que pode embutir JavaScript e causar XSS
     * armazenado quando servido inline na mesma origem da API.
     */
    private static final Set<String> TIPOS_IMAGEM_PERMITIDOS = Set.of(
            "image/png", "image/jpeg", "image/webp", "image/gif");

    private final ArquivoRepository arquivoRepository;
    private final ArmazenamentoService armazenamentoService;

    public ArquivoService(ArquivoRepository arquivoRepository, ArmazenamentoService armazenamentoService) {
        this.arquivoRepository = arquivoRepository;
        this.armazenamentoService = armazenamentoService;
    }

    @Transactional
    public ArquivoResponse enviar(MultipartFile arquivo) {
        validarImagem(arquivo);
        String caminho = armazenamentoService.salvar(arquivo, "site");
        Arquivo entidade = new Arquivo(
                arquivo.getOriginalFilename(),
                arquivo.getContentType(),
                arquivo.getSize(),
                caminho
        );
        return ArquivoResponse.de(arquivoRepository.save(entidade));
    }

    private void validarImagem(MultipartFile arquivo) {
        if (arquivo == null || arquivo.isEmpty()) {
            throw new RegraNegocioException("Arquivo vazio.");
        }
        String contentType = arquivo.getContentType();
        if (contentType == null || !TIPOS_IMAGEM_PERMITIDOS.contains(contentType)) {
            throw new RegraNegocioException("Tipo de imagem não permitido. Aceitos: PNG, JPG, WEBP, GIF.");
        }
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
