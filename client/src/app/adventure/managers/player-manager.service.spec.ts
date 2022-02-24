import {createHttpFactory, SpectatorHttp} from '@ngneat/spectator';
import {PlayerManager} from "./player-manager.service";
import {SESSION_PROVIDER, SESSION_TOKEN, SessionInterface, Token} from "../../core/security/session.service";
import {STORAGE_MANAGER_PROVIDER} from "../../core/storage/storage-manager.service";

describe('PlayerManager', () => {
  let spectator: SpectatorHttp<PlayerManager>;
  let session: SessionInterface;
  const createHttp = createHttpFactory({
    service: PlayerManager,
    providers: [SESSION_PROVIDER, STORAGE_MANAGER_PROVIDER]
  });

  beforeEach(() => {
    spectator = createHttp();
    session = spectator.inject(SESSION_TOKEN);
    spyOn(spectator.service, 'reset');
  });

  it('should reset player', () => {
    const token: Token = {
      token: 'token',
      refreshToken: 'refresh_token',
    };
    session.setToken(token);
    expect(spectator.service.reset).toHaveBeenCalled();
  });
});
