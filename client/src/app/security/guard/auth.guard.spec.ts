import {createServiceFactory, SpectatorService} from "@ngneat/spectator";
import {AuthGuard} from "./auth.guard";
import {SESSION_PROVIDER, SESSION_TOKEN, SessionInterface} from "../contracts/session";
import {RouterTestingModule} from "@angular/router/testing";
import {LOGIN_ROUTE} from "../components/login/login.component";
import {ActivatedRouteSnapshot, RouterStateSnapshot} from "@angular/router";
import {STORAGE_MANAGER_PROVIDER} from "../../shared/storage/storage-manager.service";

describe('AuthGuard', () => {
  let spectator: SpectatorService<AuthGuard>;
  let route: ActivatedRouteSnapshot;
  let session: SessionInterface;
  const fakeRouterState = (url: string): RouterStateSnapshot => {
    return {
      url,
    } as RouterStateSnapshot;
  };
  const createService = createServiceFactory({
    service: AuthGuard,
    imports: [RouterTestingModule.withRoutes([LOGIN_ROUTE])],
    providers: [SESSION_PROVIDER, STORAGE_MANAGER_PROVIDER]
  });

  beforeEach(() => {
    spectator = createService();
    session = spectator.inject(SESSION_TOKEN);
    route = {} as ActivatedRouteSnapshot;
  });

  it('should continue', () => {
    spyOn(session, 'authenticated').and.returnValue(true);
    expect(spectator.service.canActivate(route, fakeRouterState('/foo'))).toBeTrue();
  });

  it('should redirect to login', () => {
    spyOn(session, 'authenticated').and.returnValue(false);
    expect(spectator.service.canActivate(route, fakeRouterState('/foo'))).toBeFalse();
  });
});
