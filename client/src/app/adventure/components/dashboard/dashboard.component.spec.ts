import {createComponentFactory, Spectator, SpyObject} from "@ngneat/spectator";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {Observable} from "rxjs";
import {Player} from "../../entities/player";
import {PLAYER_MANAGER_TOKEN, PlayerManager, PlayerManagerInterface} from "../../managers/player-manager.service";
import {DashboardComponent} from "./dashboard.component";

describe('Dashboard component', () => {
  let spectator: Spectator<DashboardComponent>;
  let playerManager: SpyObject<PlayerManagerInterface>

  const createComponent = createComponentFactory({
    component: DashboardComponent,
    componentMocks: [PlayerManager],
    componentProviders: [
      {
        provide: PLAYER_MANAGER_TOKEN,
        useValue: {
          me: new Observable<Player | null>((subscriber) => {
            const player: Player = {
              id: 1,
              name: 'Name',
              journey: {
                id: 1,
                checkpoints: []
              }
            };
            subscriber.next(player);
            subscriber.complete();
          })
        }
      },
    ],
    imports: [HttpClientTestingModule]
  });

  beforeEach(() => {
    spectator = createComponent();
    playerManager = spectator.inject(PlayerManager, true);
  });

  it('should show player', () => {
    expect(spectator.query('h1')).toContainText('Name');
  });
});
