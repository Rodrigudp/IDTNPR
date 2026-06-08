package br.org.idtnpr.protocolo;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.JoinColumn;
import jakarta.persistence.ManyToOne;
import jakarta.persistence.Table;

import java.time.LocalDateTime;

/**
 * Arquivo anexado a um protocolo. Os bytes ficam no disco; aqui guardamos só os metadados.
 */
@Entity
@Table(name = "anexo")
public class Anexo {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @ManyToOne
    @JoinColumn(name = "protocolo_id", nullable = false)
    private Protocolo protocolo;

    @Column(name = "nome_original", nullable = false)
    private String nomeOriginal;

    @Column(name = "content_type")
    private String contentType;

    @Column(name = "tamanho_bytes")
    private Long tamanhoBytes;

    @Column(nullable = false)
    private String caminho;

    @Column(name = "criado_em", nullable = false)
    private LocalDateTime criadoEm = LocalDateTime.now();

    protected Anexo() {
    }

    public Anexo(String nomeOriginal, String contentType, Long tamanhoBytes, String caminho) {
        this.nomeOriginal = nomeOriginal;
        this.contentType = contentType;
        this.tamanhoBytes = tamanhoBytes;
        this.caminho = caminho;
    }

    public Long getId() {
        return id;
    }

    public Protocolo getProtocolo() {
        return protocolo;
    }

    void setProtocolo(Protocolo protocolo) {
        this.protocolo = protocolo;
    }

    public String getNomeOriginal() {
        return nomeOriginal;
    }

    public String getContentType() {
        return contentType;
    }

    public Long getTamanhoBytes() {
        return tamanhoBytes;
    }

    public String getCaminho() {
        return caminho;
    }

    public LocalDateTime getCriadoEm() {
        return criadoEm;
    }
}
