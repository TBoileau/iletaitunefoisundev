import {TestBed} from '@angular/core/testing';
import {STORAGE_MANAGER, StorageManagerService} from "../../../app/shared/storage/storage_manager.service";
import {Session, SESSION, SessionService, Token} from "../../../app/shared/security/session.service";

describe('SecuritySession', () => {
  let session: Session;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        {provide: STORAGE_MANAGER, useClass: StorageManagerService},
        {provide: SESSION, useClass: SessionService},
      ]
    });
    session = TestBed.inject(SESSION)
  });

  it('should be created', () => {
    expect(session).toBeTruthy();
  });

  it('should set token', () => {
    spyOn(localStorage, 'setItem');
    const token: Token = {token: 'token', refreshToken: 'refresh_token'};
    session.setToken(token);
    expect(localStorage.setItem).toHaveBeenCalledWith('token', JSON.stringify(token));
  });

  it('should get token', () => {
    const token: Token = {token: 'token', refreshToken: 'refresh_token'};
    spyOn(localStorage, 'getItem').withArgs('token').and.returnValue(JSON.stringify(token));
    expect(session.getToken()).toEqual(token);
    expect(localStorage.getItem).toHaveBeenCalledTimes(1);
  });

  it('should get token returns null', () => {
    spyOn(localStorage, 'getItem').withArgs('token').and.returnValue(null);
    expect(session.getToken()).toBeNull();
    expect(localStorage.getItem).toHaveBeenCalledTimes(1);
  });

  it('should clear', () => {
    spyOn(localStorage, 'removeItem').withArgs('token');
    session.clear();
    expect(localStorage.removeItem).toHaveBeenCalledWith('token');
  });

  it('authenticated should true', () => {
    const token: Token = {token: 'token', refreshToken: 'refresh_token'};
    spyOn(localStorage, 'getItem').withArgs('token').and.returnValue(JSON.stringify(token));
    expect(session.authenticated()).toBeTrue();
    expect(localStorage.getItem).toHaveBeenCalledTimes(1);
  });

  it('authenticated should false', () => {
    spyOn(localStorage, 'getItem').withArgs('token').and.returnValue(null);
    expect(session.authenticated()).toBeFalse();
    expect(localStorage.getItem).toHaveBeenCalledTimes(1);
  });
});
