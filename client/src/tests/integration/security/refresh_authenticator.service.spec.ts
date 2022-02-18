import {TestBed} from '@angular/core/testing';
import {STORAGE_MANAGER, StorageManagerService} from "../../../app/shared/storage/storage_manager.service";
import {Session, SESSION, SessionService, Token} from "../../../app/shared/security/session.service";
import {RefreshToken} from "../../../app/shared/security/authenticator.service";
import {HttpClient, HttpErrorResponse} from "@angular/common/http";
import {of, throwError} from "rxjs";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {RefreshAuthenticatorService} from "../../../app/shared/security/refresh_authenticator.service";

describe('RefreshAuthenticator', () => {
  let session: Session;
  let authenticator: RefreshAuthenticatorService;
  let http: HttpClient;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [
        HttpClientTestingModule,
      ],
      providers: [
        {provide: STORAGE_MANAGER, useClass: StorageManagerService},
        {provide: SESSION, useClass: SessionService},
        {provide: RefreshAuthenticatorService},
      ]
    });
    session = TestBed.inject(SESSION)
    authenticator = TestBed.inject(RefreshAuthenticatorService)
    http = TestBed.inject(HttpClient)
  });

  it('should be created', () => {
    expect(authenticator).toBeTruthy();
  });

  it('should supports', () => {
    spyOn(session, 'authenticated').and.returnValue(true);

    expect(authenticator.supports()).toBeTrue();
  });

  it('should not supports', () => {
    spyOn(session, 'authenticated').and.returnValue(false);

    expect(authenticator.supports()).toBeFalse();
  });

  it('should set token to session', () => {
    const token: Token = {
      token: 'token',
      refreshToken: 'refresh_token'
    };
    const refreshToken: RefreshToken = {
      refreshToken: 'refresh_token'
    };
    spyOn(session, 'setToken');
    spyOn(http, 'post').withArgs('/api/security/token-refresh', refreshToken).and.returnValue(of(token));
    authenticator.authenticate(refreshToken).subscribe();
    expect(session.setToken).toHaveBeenCalledWith(token);
  });

  it('should clear session', () => {
    const token: Token = {
      token: 'token',
      refreshToken: 'refresh_token'
    };
    const refreshToken: RefreshToken = {
      refreshToken: 'refresh_token'
    };
    spyOn(session, 'clear');
    spyOn(http, 'post').withArgs('/api/security/token-refresh', refreshToken).and.returnValue(throwError(() => new HttpErrorResponse({})));
    authenticator.authenticate(refreshToken).subscribe();
    expect(session.clear).toHaveBeenCalled();
  });
});
