package br.org.idtnpr.auth;

import org.springframework.stereotype.Service;

import java.time.Duration;
import java.time.Instant;
import java.util.Map;
import java.util.concurrent.ConcurrentHashMap;

/**
 * Limita tentativas de login por cliente (chave = IP), mitigando força bruta.
 *
 * <p>Após {@link #MAX_TENTATIVAS} falhas dentro da janela de {@link #JANELA},
 * o cliente fica bloqueado até a janela expirar. Um login bem-sucedido zera o contador.</p>
 *
 * <p>Implementação em memória (suficiente para uma instância). Para múltiplas
 * instâncias/produção, trocar por Caffeine com expiração ou Redis (ver TAREFAS.md).</p>
 */
@Service
public class LoginAttemptService {

    private static final int MAX_TENTATIVAS = 5;
    private static final Duration JANELA = Duration.ofMinutes(15);

    private record Tentativas(int contador, Instant inicioJanela) {
    }

    private final Map<String, Tentativas> porChave = new ConcurrentHashMap<>();

    public boolean bloqueado(String chave) {
        Tentativas t = porChave.get(chave);
        if (t == null) {
            return false;
        }
        if (janelaExpirada(t)) {
            porChave.remove(chave);
            return false;
        }
        return t.contador() >= MAX_TENTATIVAS;
    }

    public void registrarFalha(String chave) {
        Instant agora = Instant.now();
        porChave.compute(chave, (k, t) -> {
            if (t == null || janelaExpirada(t)) {
                return new Tentativas(1, agora);
            }
            return new Tentativas(t.contador() + 1, t.inicioJanela());
        });
    }

    public void registrarSucesso(String chave) {
        porChave.remove(chave);
    }

    private boolean janelaExpirada(Tentativas t) {
        return Instant.now().isAfter(t.inicioJanela().plus(JANELA));
    }
}
