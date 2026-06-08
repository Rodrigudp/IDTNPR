package br.org.idtnpr.contato;

import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;

public interface MensagemContatoRepository extends JpaRepository<MensagemContato, Long> {

    Page<MensagemContato> findByLida(boolean lida, Pageable pageable);

    long countByLidaFalse();
}
