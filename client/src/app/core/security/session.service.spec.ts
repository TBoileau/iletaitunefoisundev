import {createServiceFactory, SpectatorService} from "@ngneat/spectator";
import {Session, Token} from "./session.service";
import {STORAGE_MANAGER_PROVIDER} from "../storage/storage-manager.service";

describe('StorageManager', () => {
  let spectator: SpectatorService<Session>;
  const createService = createServiceFactory({
    service: Session,
    providers: [STORAGE_MANAGER_PROVIDER]
  });

  beforeEach(() => spectator = createService());

  it('should clear session and not be authenticated', () => {
    spyOn(localStorage, 'removeItem');
    spyOn(localStorage, 'getItem').and.returnValue(null);
    spectator.service.clear();
    expect(localStorage.removeItem).toHaveBeenCalledOnceWith('token');
    expect(spectator.service.authenticated()).toBeFalse();
  });

  it('should return token and be authenticated', () => {
    const token: Token = {
      token: 'token',
      refreshToken: 'refresh_token',
    };
    spyOn(localStorage, 'getItem').withArgs('token').and.returnValue(JSON.stringify(token));
    expect(spectator.service.getToken()).toEqual(token);
    expect(localStorage.getItem).toHaveBeenCalledOnceWith('token');
    expect(spectator.service.authenticated()).toBeTrue();
  });

  it('should set token', () => {
    const token: Token = {
      token: 'token',
      refreshToken: 'refresh_token',
    };
    spyOn(localStorage, 'setItem');
    spectator.service.setToken(token);
    expect(localStorage.setItem).toHaveBeenCalledOnceWith('token', JSON.stringify(token));
  });
});
