package br.org.idtnpr.arquivo;

import br.org.idtnpr.common.RegraNegocioException;
import br.org.idtnpr.config.AppProperties;
import jakarta.annotation.PostConstruct;
import org.springframework.stereotype.Service;
import org.springframework.util.StringUtils;
import org.springframework.web.multipart.MultipartFile;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.UUID;

/**
 * Grava e lê arquivos no disco local. Cada arquivo recebe um nome único para
 * evitar colisões; o caminho relativo gerado é o que deve ser persistido.
 */
@Service
public class ArmazenamentoService {

    private final Path raiz;

    public ArmazenamentoService(AppProperties appProperties) {
        this.raiz = Paths.get(appProperties.storageDir()).toAbsolutePath().normalize();
    }

    @PostConstruct
    void init() {
        try {
            Files.createDirectories(raiz);
        } catch (IOException e) {
            throw new IllegalStateException("Não foi possível criar o diretório de uploads: " + raiz, e);
        }
    }

    /**
     * Salva o arquivo em uma subpasta (ex.: "protocolos", "site") e devolve o
     * caminho relativo gravado (que vai para o banco).
     */
    public String salvar(MultipartFile arquivo, String subpasta) {
        if (arquivo == null || arquivo.isEmpty()) {
            throw new RegraNegocioException("Arquivo vazio.");
        }
        try {
            Path diretorio = raiz.resolve(subpasta).normalize();
            Files.createDirectories(diretorio);

            String extensao = StringUtils.getFilenameExtension(arquivo.getOriginalFilename());
            String nomeFisico = UUID.randomUUID() + (extensao != null ? "." + extensao : "");
            Path destino = diretorio.resolve(nomeFisico);

            arquivo.transferTo(destino);
            return raiz.relativize(destino).toString().replace('\\', '/');
        } catch (IOException e) {
            throw new RegraNegocioException("Falha ao salvar o arquivo: " + e.getMessage());
        }
    }

    /** Resolve um caminho relativo (do banco) para um Path absoluto seguro dentro da raiz. */
    public Path resolver(String caminhoRelativo) {
        Path resolvido = raiz.resolve(caminhoRelativo).normalize();
        if (!resolvido.startsWith(raiz)) {
            throw new RegraNegocioException("Caminho de arquivo inválido.");
        }
        return resolvido;
    }
}
