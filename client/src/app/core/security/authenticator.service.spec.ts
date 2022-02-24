import {createHttpFactory, HttpMethod, SpectatorHttp} from '@ngneat/spectator';
import {SESSION_PROVIDER, SESSION_TOKEN, SessionInterface, Token} from "./session.service";
import {Authenticator, Credentials} from "./authenticator.service";
import {STORAGE_MANAGER_PROVIDER} from "../storage/storage-manager.service";

describe('Authenticator', () => {
  let spectator: SpectatorHttp<Authenticator>;
  let session: SessionInterface;
  const createHttp = createHttpFactory({
    service: Authenticator,
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
    expect(session.setToken).toHaveBeenCalledWith(token);
  });

  it('should refresh token and return token', () => {
    const token: Token = {
      token: 'token',
      refreshToken: 'refreshToken',
    };
    spectator.service.refreshToken(token).subscribe();
    const request = spectator.expectOne('/api/security/token-refresh', HttpMethod.POST);
    request.flush(token);
    expect(session.setToken).toHaveBeenCalledWith(token);
  });
});
