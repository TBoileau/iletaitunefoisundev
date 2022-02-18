import {TestBed} from '@angular/core/testing';
import {STORAGE_MANAGER, StorageManagerService} from "../../../app/shared/storage/storage_manager.service";
import {Session, SESSION, SessionService, Token} from "../../../app/shared/security/session.service";
import {Credentials} from "../../../app/shared/security/authenticator.service";
import {HttpClient, HttpErrorResponse} from "@angular/common/http";
import {of, throwError} from "rxjs";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {RouterTestingModule} from "@angular/router/testing";
import {ActivatedRoute, Router} from "@angular/router";
import {LoginAuthenticatorService} from "../../../app/shared/security/login_authenticator.service";

describe('LoginAuthenticator', () => {
  let session: Session;
  let authenticator: LoginAuthenticatorService;
  let http: HttpClient;
  let router: Router;
  let route: ActivatedRoute;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [
        HttpClientTestingModule,
        RouterTestingModule
      ],
      providers: [
        {provide: STORAGE_MANAGER, useClass: StorageManagerService},
        {provide: SESSION, useClass: SessionService},
        {provide: LoginAuthenticatorService},
      ]
    });
    session = TestBed.inject(SESSION)
    authenticator = TestBed.inject(LoginAuthenticatorService)
    http = TestBed.inject(HttpClient)
    router = TestBed.inject(Router)
    route = TestBed.inject(ActivatedRoute)
  });

  it('should be created', () => {
    expect(authenticator).toBeTruthy();
  });

  it('should supports', () => {
    spyOn(session, 'authenticated').and.returnValue(true);

    expect(authenticator.supports()).toBeFalse();
  });

  it('should not supports', () => {
    spyOn(session, 'authenticated').and.returnValue(false);

    expect(authenticator.supports()).toBeTrue();
  });

  it('should authenticate and create session', () => {
    const token: Token = {
      token: 'token',
      refreshToken: 'refresh_token'
    };
    const credentials: Credentials = {
      email: 'email@email.com',
      password: 'password'
    };
    spyOn(authenticator.onAuthentication, 'next');
    spyOn(session, 'setToken');
    spyOn(http, 'post').withArgs('/api/security/login', credentials).and.returnValue(of(token));
    authenticator.authenticate(credentials);
    expect(session.setToken).toHaveBeenCalledWith(token);
    expect(authenticator.onAuthentication.next).toHaveBeenCalled();
  });

  it('should raise en error when authenticate', () => {
    spyOn(authenticator.onAuthentication, 'error');
    spyOn(http, 'post').and.returnValue(throwError(() => new HttpErrorResponse({})));
    const credentials: Credentials = {
      email: 'email@email.com',
      password: 'password'
    };
    authenticator.authenticate(credentials);
    expect(authenticator.onAuthentication.error).toHaveBeenCalled();
  });
});
