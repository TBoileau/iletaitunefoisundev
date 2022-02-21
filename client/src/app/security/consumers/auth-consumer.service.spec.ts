import {createHttpFactory, HttpMethod, SpectatorHttp} from '@ngneat/spectator';
import {Credentials} from "../contracts/login";
import {AuthConsumer} from "./auth-consumer.service";
import {SESSION_PROVIDER, SESSION_TOKEN, SessionInterface, Token} from "../contracts/session";
import {STORAGE_MANAGER_PROVIDER} from "../../shared/storage/storage-manager.service";


describe('Auth consumer', () => {
  let spectator: SpectatorHttp<AuthConsumer>;
  let session: SessionInterface;
  const createHttp = createHttpFactory({
    service: AuthConsumer,
    providers: [SESSION_PROVIDER, STORAGE_MANAGER_PROVIDER]
  });

  beforeEach(() => {
    spectator = createHttp();
    session = spectator.inject(SESSION_TOKEN);
    spyOn(session, 'setToken');
  });

  it('should login and return token', () => {
    const credentials: Credentials = {
      email: 'user@email.com',
      password: 'password'
    };
    spectator.service.login(credentials).subscribe();
    const request = spectator.expectOne('/api/security/login', HttpMethod.POST);
    const token = {
      token: 'token',
      refreshToken: 'refreshToken',
    };
    request.flush(token);
    expect(session.setToken).toHaveBeenCalledWith(token)
  });

  it('should refresh token and return token', () => {
    const token: Token = {
      token: 'token',
      refreshToken: 'refreshToken',
    };
    spectator.service.refreshToken(token).subscribe();
    const request = spectator.expectOne('/api/security/token-refresh', HttpMethod.POST);
    request.flush(token);
    expect(session.setToken).toHaveBeenCalledWith(token)
  });
});
