package br.org.idtnpr.contato;

import br.org.idtnpr.common.RecursoNaoEncontradoException;
import br.org.idtnpr.contato.dto.MensagemContatoRequest;
import br.org.idtnpr.contato.dto.MensagemContatoResponse;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

@Service
public class ContatoService {

    private final MensagemContatoRepository repository;

    public ContatoService(MensagemContatoRepository repository) {
        this.repository = repository;
    }

    @Transactional
    public MensagemContatoResponse registrar(MensagemContatoRequest req) {
        MensagemContato mensagem = new MensagemContato(req.nome(), req.email(), req.telefone(), req.mensagem());
        return MensagemContatoResponse.de(repository.save(mensagem));
    }

    @Transactional(readOnly = true)
    public Page<MensagemContatoResponse> listar(Boolean lida, Pageable pageable) {
        Page<MensagemContato> page = (lida == null)
                ? repository.findAll(pageable)
                : repository.findByLida(lida, pageable);
        return page.map(MensagemContatoResponse::de);
    }

    @Transactional
    public MensagemContatoResponse marcarComoLida(Long id) {
        MensagemContato mensagem = repository.findById(id)
                .orElseThrow(() -> new RecursoNaoEncontradoException("Mensagem não encontrada: " + id));
        mensagem.setLida(true);
        return MensagemContatoResponse.de(repository.save(mensagem));
    }
}
