import {createHttpFactory, HttpMethod, SpectatorHttp} from '@ngneat/spectator';
import {RegionManager} from "./region-manager.service";

describe('WorldManager', () => {
  let spectator: SpectatorHttp<RegionManager>;
  const createHttp = createHttpFactory(RegionManager);

  beforeEach(() => spectator = createHttp());

  it('should get quests by region', () => {
    spectator.service.getMapByRegion(1).subscribe();
    const request = spectator.expectOne('/api/adventure/regions/1/map', HttpMethod.GET);
    request.flush([]);
  });
});
