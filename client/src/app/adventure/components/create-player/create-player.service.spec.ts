import {createHttpFactory, HttpMethod, SpectatorHttp} from '@ngneat/spectator';
import {CreatePlayer, NewPlayer} from "./create-player.service";
import {PLAYER_MANAGER_PROVIDER} from "../../managers/player-manager.service";
import {SESSION_PROVIDER} from "../../../core/security/session.service";
import {STORAGE_MANAGER_PROVIDER} from "../../../core/storage/storage-manager.service";

describe('Create player', () => {
  let spectator: SpectatorHttp<CreatePlayer>;
  const createHttp = createHttpFactory({
    service: CreatePlayer,
    providers: [PLAYER_MANAGER_PROVIDER, SESSION_PROVIDER, STORAGE_MANAGER_PROVIDER]
  });

  beforeEach(() => {
    spectator = createHttp();
  });

  it('should reset player', () => {
    const newPlayer: NewPlayer = {
      name: 'Player'
    };
    spectator.service.create(newPlayer).subscribe(() => {
    });
    spectator.expectOne('/api/adventure/players', HttpMethod.POST);
  });
});
