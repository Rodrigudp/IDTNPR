package br.org.idtnpr.auth;

import br.org.idtnpr.auth.dto.LoginRequest;
import br.org.idtnpr.auth.dto.TokenResponse;
import br.org.idtnpr.common.RateLimitExcedidoException;
import io.swagger.v3.oas.annotations.Operation;
import io.swagger.v3.oas.annotations.tags.Tag;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.validation.Valid;
import org.springframework.http.ResponseEntity;
import org.springframework.security.authentication.AuthenticationManager;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.AuthenticationException;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

/**
 * Endpoints públicos de autenticação.
 */
@RestController
@RequestMapping("/api/auth")
@Tag(name = "Autenticação")
public class AuthController {

    private final AuthenticationManager authenticationManager;
    private final TokenService tokenService;
    private final LoginAttemptService loginAttemptService;

    public AuthController(AuthenticationManager authenticationManager,
                          TokenService tokenService,
                          LoginAttemptService loginAttemptService) {
        this.authenticationManager = authenticationManager;
        this.tokenService = tokenService;
        this.loginAttemptService = loginAttemptService;
    }

    @PostMapping("/login")
    @Operation(summary = "Autentica um usuário admin e retorna um token JWT")
    public ResponseEntity<TokenResponse> login(@Valid @RequestBody LoginRequest request,
                                               HttpServletRequest httpRequest) {
        String chave = httpRequest.getRemoteAddr();

        // Bloqueia força bruta: muitas falhas a partir do mesmo IP.
        if (loginAttemptService.bloqueado(chave)) {
            throw new RateLimitExcedidoException(
                    "Muitas tentativas de login. Tente novamente em alguns minutos.");
        }

        try {
            Authentication authentication = authenticationManager.authenticate(
                    new UsernamePasswordAuthenticationToken(request.email(), request.senha()));
            loginAttemptService.registrarSucesso(chave);

            String token = tokenService.gerarToken(authentication);
            return ResponseEntity.ok(TokenResponse.bearer(token, tokenService.getExpirationSeconds()));
        } catch (AuthenticationException ex) {
            loginAttemptService.registrarFalha(chave);
            throw ex; // tratado pelo ApiExceptionHandler como 401
        }
    }
}
