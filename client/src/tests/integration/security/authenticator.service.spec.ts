import {TestBed} from '@angular/core/testing';
import {STORAGE_MANAGER, StorageManagerService} from "../../../app/shared/storage/storage_manager.service";
import {Session, SESSION, SessionService, Token} from "../../../app/shared/security/session.service";
import {
  AUTHENTICATOR,
  Authenticator,
  AuthenticatorService,
  Credentials
} from "../../../app/shared/security/authenticator.service";
import {HttpClient, HttpErrorResponse} from "@angular/common/http";
import {of, throwError} from "rxjs";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {RouterTestingModule} from "@angular/router/testing";
import {ActivatedRoute, Router} from "@angular/router";

describe('Authenticator', () => {
  let session: Session;
  let authenticator: Authenticator;
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
        {provide: AUTHENTICATOR, useClass: AuthenticatorService},
      ]
    });
    session = TestBed.inject(SESSION)
    authenticator = TestBed.inject(AUTHENTICATOR)
    http = TestBed.inject(HttpClient)
    router = TestBed.inject(Router)
    route = TestBed.inject(ActivatedRoute)
  });

  it('should be created', () => {
    expect(authenticator).toBeTruthy();
  });

  it('should authenticate and create session', () => {
    const token = <Token>{
      token: 'token',
      refreshToken: 'refresh_token'
    };
    const credentials = <Credentials>{
      email: 'email@email.com',
      password: 'password'
    };
    spyOn(session, 'setToken');
    spyOn(http, 'post').withArgs('/api/security/login', credentials).and.returnValue(of(token));
    authenticator.authenticate(credentials, () => {
    });
    expect(session.setToken).toHaveBeenCalledWith(token);
  });

  it('should raise en error when authenticate', () => {
    const onFailure = jasmine.createSpy();
    spyOn(http, 'post').and.returnValue(throwError(() => new HttpErrorResponse({})));
    authenticator.authenticate(<Credentials>{
      email: 'email@email.com',
      password: 'password'
    }, onFailure);
    expect(onFailure).toHaveBeenCalled();
  });

  it('should refresh token', () => {
    spyOn(session, 'getToken').and.returnValue(<Token>{
      token: 'token',
      refreshToken: 'refresh_token'
    });
    const newToken = <Token>{
      token: 'new_token',
      refreshToken: 'new_refresh_token'
    };
    spyOn(http, 'post')
      .withArgs('/api/security/token-refresh', {refreshToken: 'refresh_token'})
      .and.returnValue(of(newToken));
    spyOn(session, 'setToken');
    authenticator.refresh().subscribe();
    expect(session.setToken).toHaveBeenCalledWith(newToken);
  });

  it('should return login url', () => {
    expect(authenticator.getLoginUrl()).toBe('/login');
  });

  it('should return login url', () => {
    spyOn(session, 'authenticated').and.returnValue(false);
    expect(authenticator.supports()).toBe(true);
  });

  it('should redirect to home', () => {
    spyOn(router, 'navigate');
    authenticator.onAuthenticationSuccess();
    expect(router.navigate).toHaveBeenCalledWith(['/']);
  });

  it('should redirect to test', () => {
    spyOn(router, 'navigate');
    route.snapshot.queryParams = {return: '/test'}
    authenticator.onAuthenticationSuccess();
    expect(router.navigate).toHaveBeenCalledWith(['/test']);
  });
});
