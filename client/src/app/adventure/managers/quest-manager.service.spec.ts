import {createHttpFactory, HttpMethod, SpectatorHttp} from '@ngneat/spectator';
import {QuestManager} from "./quest-manager.service";
import {Region} from "../entities/region";

describe('WorldManager', () => {
  let spectator: SpectatorHttp<QuestManager>;
  const createHttp = createHttpFactory(QuestManager);

  beforeEach(() => spectator = createHttp());

  it('should get quests by region', () => {
    const region: Region = {
      id: 1,
      name: 'Region'
    };
    spectator.service.getQuestsByRegion(region).subscribe();
    const request = spectator.expectOne('/api/adventure/regions/1/quests', HttpMethod.GET);
    request.flush([]);
  });
});
