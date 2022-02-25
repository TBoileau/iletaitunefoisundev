import {createHttpFactory, HttpMethod, SpectatorHttp} from '@ngneat/spectator';
import {Region} from "../entities/region";
import {RegionManager} from "./region-manager.service";

describe('WorldManager', () => {
  let spectator: SpectatorHttp<RegionManager>;
  const createHttp = createHttpFactory(RegionManager);

  beforeEach(() => spectator = createHttp());

  it('should get quests by region', () => {
    const region: Region = {
      id: 1,
      name: 'Region'
    };
    spectator.service.getMapByRegion(region).subscribe();
    const request = spectator.expectOne('/api/adventure/regions/1/map', HttpMethod.GET);
    request.flush([]);
  });
});
