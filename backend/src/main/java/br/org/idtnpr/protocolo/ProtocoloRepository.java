package br.org.idtnpr.protocolo;

import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.Optional;

public interface ProtocoloRepository extends JpaRepository<Protocolo, Long> {

    Optional<Protocolo> findByNumero(String numero);

    boolean existsByNumero(String numero);

    Page<Protocolo> findByStatus(StatusProtocolo status, Pageable pageable);
}
