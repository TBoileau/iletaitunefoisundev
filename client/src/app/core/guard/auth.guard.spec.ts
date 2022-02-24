import {createServiceFactory, SpectatorService} from "@ngneat/spectator";
import {AuthGuard} from "./auth.guard";
import {RouterTestingModule} from "@angular/router/testing";
import {ActivatedRouteSnapshot, RouterStateSnapshot} from "@angular/router";
import {SESSION_PROVIDER, SESSION_TOKEN, SessionInterface} from "../security/session.service";
import {STORAGE_MANAGER_PROVIDER} from "../storage/storage-manager.service";
import {LOGIN_ROUTE} from "../../security/components/login/login.component";

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
