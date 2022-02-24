import {createHttpFactory, HttpMethod, SpectatorHttp} from "@ngneat/spectator";
import {RouterTestingModule} from "@angular/router/testing";
import {ActivatedRouteSnapshot, RouterStateSnapshot} from "@angular/router";
import {LOGIN_ROUTE} from "../../security/components/login/login.component";
import {PlayerGuard} from "./player.guard";
import {PLAYER_MANAGER_PROVIDER} from "../managers/player-manager.service";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {Player} from "../entities/player";
import {SESSION_PROVIDER} from "../../core/security/session.service";
import {STORAGE_MANAGER_PROVIDER} from "../../core/storage/storage-manager.service";
import {CREATE_PLAYER_ROUTE} from "../components/create-player/create-player.component";

describe('PlayerGuard', () => {
  let spectator: SpectatorHttp<PlayerGuard>;
  let route: ActivatedRouteSnapshot;
  const fakeRouterState = (url: string): RouterStateSnapshot => {
    return {
      url,
    } as RouterStateSnapshot;
  };
  const createHttp = createHttpFactory({
    service: PlayerGuard,
    imports: [
      RouterTestingModule.withRoutes([CREATE_PLAYER_ROUTE, LOGIN_ROUTE]),
      HttpClientTestingModule
    ],
    providers: [PLAYER_MANAGER_PROVIDER, SESSION_PROVIDER, STORAGE_MANAGER_PROVIDER]
  });

  beforeEach(() => {
    spectator = createHttp();
    route = {} as ActivatedRouteSnapshot;
  });

  it('should continue', () => {
    const player: Player = {
      id: 1,
      name: 'Name',
      journey: {
        id: 1,
        checkpoints: []
      }
    };
    spectator.service.canActivate(route, fakeRouterState('/dashboard')).subscribe(canContinue => {
      expect(canContinue).toBeTrue();
    });
    const request = spectator.expectOne('/api/adventure/players/me', HttpMethod.GET);
    request.flush(player);
  });

  it('should redirect to create-player', () => {
    spectator.service.canActivate(route, fakeRouterState('/dashboard')).subscribe(canContinue => {
      expect(canContinue).toBeFalse();
    });
    const request = spectator.expectOne('/api/adventure/players/me', HttpMethod.GET);
    request.flush(null);
  });
});
