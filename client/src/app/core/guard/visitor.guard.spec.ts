import {createServiceFactory, SpectatorService} from "@ngneat/spectator";
import {RouterTestingModule} from "@angular/router/testing";
import {ActivatedRouteSnapshot, RouterStateSnapshot} from "@angular/router";
import {VisitorGuard} from "./visitor.guard";
import {SESSION_PROVIDER, SESSION_TOKEN, SessionInterface} from "../security/session.service";
import {STORAGE_MANAGER_PROVIDER} from "../storage/storage-manager.service";
import {LOGIN_ROUTE} from "../../security/components/login/login.component";

describe('VisitorGuard', () => {
  let spectator: SpectatorService<VisitorGuard>;
  let route: ActivatedRouteSnapshot;
  let session: SessionInterface;
  const fakeRouterState = (url: string): RouterStateSnapshot => {
    return {
      url,
    } as RouterStateSnapshot;
  };
  const createService = createServiceFactory({
    service: VisitorGuard,
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
    expect(spectator.service.canActivate(route, fakeRouterState('/foo'))).toBeFalse();
  });

  it('should redirect to home', () => {
    spyOn(session, 'authenticated').and.returnValue(false);
    expect(spectator.service.canActivate(route, fakeRouterState('/foo'))).toBeTrue();
  });
});
