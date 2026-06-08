package br.org.idtnpr.protocolo;

import br.org.idtnpr.arquivo.ArmazenamentoService;
import br.org.idtnpr.common.RecursoNaoEncontradoException;
import br.org.idtnpr.common.RegraNegocioException;
import br.org.idtnpr.protocolo.dto.CriarProtocoloRequest;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import org.springframework.web.multipart.MultipartFile;

import java.math.BigInteger;
import java.security.SecureRandom;
import java.time.Year;
import java.util.Set;

/**
 * Regras de negócio dos protocolos: abertura, acompanhamento, anexos e mudança de status.
 */
@Service
public class ProtocoloService {

    /** Tipos de anexo aceitos (espelha o "PDF, JPG, PNG" informado no formulário). */
    private static final Set<String> TIPOS_ANEXO_PERMITIDOS = Set.of(
            "application/pdf", "image/jpeg", "image/png");

    /** Gerador criptograficamente forte para o número (capability) do protocolo. */
    private static final SecureRandom RANDOM = new SecureRandom();

    private final ProtocoloRepository protocoloRepository;
    private final ArmazenamentoService armazenamentoService;

    public ProtocoloService(ProtocoloRepository protocoloRepository,
                            ArmazenamentoService armazenamentoService) {
        this.protocoloRepository = protocoloRepository;
        this.armazenamentoService = armazenamentoService;
    }

    @Transactional
    public Protocolo abrir(CriarProtocoloRequest req) {
        Protocolo protocolo = new Protocolo(
                gerarNumero(),
                req.nome(),
                req.cpf(),
                req.email(),
                req.telefone(),
                req.tipoSolicitacao(),
                req.descricao()
        );
        return protocoloRepository.save(protocolo);
    }

    @Transactional(readOnly = true)
    public Protocolo buscarPorNumero(String numero) {
        return protocoloRepository.findByNumero(numero)
                .orElseThrow(() -> new RecursoNaoEncontradoException("Protocolo não encontrado: " + numero));
    }

    @Transactional(readOnly = true)
    public Page<Protocolo> listar(StatusProtocolo status, Pageable pageable) {
        return status == null
                ? protocoloRepository.findAll(pageable)
                : protocoloRepository.findByStatus(status, pageable);
    }

    @Transactional
    public Protocolo anexar(String numero, MultipartFile arquivo) {
        Protocolo protocolo = buscarPorNumero(numero);
        validarTipoAnexo(arquivo);
        String caminho = armazenamentoService.salvar(arquivo, "protocolos");
        Anexo anexo = new Anexo(
                arquivo.getOriginalFilename(),
                arquivo.getContentType(),
                arquivo.getSize(),
                caminho
        );
        protocolo.adicionarAnexo(anexo);
        return protocoloRepository.save(protocolo);
    }

    @Transactional
    public Protocolo atualizarStatus(String numero, StatusProtocolo novoStatus) {
        Protocolo protocolo = buscarPorNumero(numero);
        StatusProtocolo atual = protocolo.getStatus();

        // Regra: só permite transições válidas (ver StatusProtocolo.transicoesPermitidas()).
        if (atual != novoStatus && !atual.podeTransicionarPara(novoStatus)) {
            throw new RegraNegocioException(
                    "Transição de status inválida: %s -> %s".formatted(atual, novoStatus));
        }
        protocolo.setStatus(novoStatus);
        return protocoloRepository.save(protocolo);
    }

    /**
     * Gera o número público do protocolo.
     *
     * <p><b>Segurança:</b> o número é o "ingresso" (capability) que o cidadão usa para
     * consultar e anexar arquivos sem login. Por isso ele <b>não pode ser adivinhável</b> —
     * um número sequencial ({@code 2026-000123}) permitiria a qualquer um enumerar e
     * vazar dados pessoais (CPF, e-mail) de outras pessoas (vulnerabilidade IDOR).
     * Usamos ~100 bits de entropia de {@link SecureRandom}, mantendo o ano como prefixo
     * apenas para leitura humana.</p>
     *
     * <p>TODO (você): você ainda pode lapidar o <i>formato</i> (tamanho, separadores,
     * caixa), mas mantenha a propriedade de ser imprevisível. Para volume alto, considere
     * também emitir um número sequencial legível <i>separado</i> só para uso interno/admin.</p>
     */
    private String gerarNumero() {
        String prefixo = Year.now() + "-";
        String numero;
        do {
            // 100 bits em base 32 (~20 caracteres) — inviável de adivinhar/enumerar.
            String token = new BigInteger(100, RANDOM).toString(32).toUpperCase();
            numero = prefixo + token;
        } while (protocoloRepository.existsByNumero(numero));
        return numero;
    }

    private void validarTipoAnexo(MultipartFile arquivo) {
        if (arquivo == null || arquivo.isEmpty()) {
            throw new RegraNegocioException("Arquivo vazio.");
        }
        String contentType = arquivo.getContentType();
        if (contentType == null || !TIPOS_ANEXO_PERMITIDOS.contains(contentType)) {
            throw new RegraNegocioException("Tipo de arquivo não permitido. Aceitos: PDF, JPG, PNG.");
        }
    }
}
