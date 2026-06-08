package br.org.idtnpr.config;

import br.org.idtnpr.usuario.Role;
import br.org.idtnpr.usuario.Usuario;
import br.org.idtnpr.usuario.UsuarioRepository;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.boot.CommandLineRunner;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Component;

/**
 * Cria o usuário administrador inicial na primeira execução, caso ainda não exista.
 * As credenciais vêm de {@link AppProperties} (variáveis de ambiente em produção).
 */
@Component
public class DataSeeder implements CommandLineRunner {

    private static final Logger log = LoggerFactory.getLogger(DataSeeder.class);

    private final UsuarioRepository usuarioRepository;
    private final PasswordEncoder passwordEncoder;
    private final AppProperties appProperties;

    public DataSeeder(UsuarioRepository usuarioRepository,
                      PasswordEncoder passwordEncoder,
                      AppProperties appProperties) {
        this.usuarioRepository = usuarioRepository;
        this.passwordEncoder = passwordEncoder;
        this.appProperties = appProperties;
    }

    @Override
    public void run(String... args) {
        AppProperties.Admin admin = appProperties.admin();
        if (usuarioRepository.existsByEmail(admin.email())) {
            return;
        }
        Usuario usuario = new Usuario(
                admin.nome(),
                admin.email(),
                passwordEncoder.encode(admin.senha()),
                Role.ADMIN
        );
        usuarioRepository.save(usuario);
        log.info("Usuário admin inicial criado: {}", admin.email());
    }
}
