import {createHttpFactory, HttpMethod, SpectatorHttp} from '@ngneat/spectator';
import {PlayerManager} from "./player-manager.service";
import {SESSION_PROVIDER} from "../../core/security/session.service";
import {STORAGE_MANAGER_PROVIDER} from "../../core/storage/storage-manager.service";

describe('PlayerManager', () => {
  let spectator: SpectatorHttp<PlayerManager>;
  const createHttp = createHttpFactory({
    service: PlayerManager,
    providers: [SESSION_PROVIDER, STORAGE_MANAGER_PROVIDER]
  });

  beforeEach(() => spectator = createHttp());

  it('should reset player', () => {
    spectator.service.reset();
    spectator.service.me.subscribe();
    spectator.expectOne('/api/adventure/players/me', HttpMethod.GET);
  });
});
