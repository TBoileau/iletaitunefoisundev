import {createHttpFactory, HttpMethod, SpectatorHttp} from '@ngneat/spectator';
import {WorldManager} from "./world-manager.service";

describe('WorldManager', () => {
  let spectator: SpectatorHttp<WorldManager>;
  const createHttp = createHttpFactory(WorldManager);

  beforeEach(() => spectator = createHttp());

  it('should get worlds', () => {
    spectator.service.getWorlds().subscribe();
    const request = spectator.expectOne('/api/adventure/worlds', HttpMethod.GET);
    request.flush([]);
  });
});
