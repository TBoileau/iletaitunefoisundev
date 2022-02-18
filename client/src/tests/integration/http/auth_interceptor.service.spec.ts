import {inject, TestBed} from '@angular/core/testing';
import {HTTP_INTERCEPTORS, HttpClient} from "@angular/common/http";
import {HttpClientTestingModule, HttpTestingController} from "@angular/common/http/testing";
import {SESSION, Session, SessionService, Token} from "../../../app/shared/security/session.service";
import {STORAGE_MANAGER, StorageManagerService} from "../../../app/shared/storage/storage_manager.service";
import {AuthInterceptor} from "../../../app/shared/http/auth_interceptor.service";
import {RouterTestingModule} from "@angular/router/testing";
import {RefreshAuthenticatorService} from "../../../app/shared/security/refresh_authenticator.service";
import {Router} from "@angular/router";
import {LoginComponent} from "../../../app/security/login/login.component";

describe('AuthInterceptor', () => {
  let httpMock: HttpTestingController;
  let session: Session;
  let authenticator: RefreshAuthenticatorService;
  let router: Router;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [
        HttpClientTestingModule,
        RouterTestingModule.withRoutes([
          {path: 'login', component: LoginComponent},
        ]),
      ],
      providers: [
        {provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true},
        {provide: STORAGE_MANAGER, useClass: StorageManagerService},
        {provide: RefreshAuthenticatorService},
        {provide: SESSION, useClass: SessionService},
      ]
    });
    httpMock = TestBed.inject(HttpTestingController);
    session = TestBed.inject(SESSION);
    router = TestBed.inject(Router);
    authenticator = TestBed.inject(RefreshAuthenticatorService);
  });


  it('should add bearer token to Authorization header', inject([HttpClient], (http: HttpClient) => {
    const token: Token = {
      token: 'token',
      refreshToken: 'refresh_token'
    };
    spyOn(session, 'getToken').and.returnValue(token);
    spyOn(session, 'authenticated').and.returnValue(true);
    http.get('/api').subscribe();
    const request = httpMock.expectOne('/api');
    expect(request.request.headers.get('Authorization')).toBe('Bearer token');
  }));

  it('should add bearer token after refresh to Authorization header', inject([HttpClient], (http: HttpClient) => {
    const token: Token = {
      token: 'token',
      refreshToken: 'refresh_token'
    };
    spyOn(session, 'getToken').and.returnValue(token);
    spyOn(authenticator, 'supports').and.returnValue(true);
    http.get('/api').subscribe();
    const request = httpMock.expectOne('/api');
    request.flush("", {status: 401, statusText: "JWT expired"});
    expect(request.request.headers.get('Authorization')).toBe('Bearer token');
  }));
  //
  // it('should clear session if refresh token has expired', inject([HttpClient], (http: HttpClient) => {
  //   const token: Token = {
  //     token: 'token',
  //     refreshToken: 'refresh_token'
  //   };
  //   spyOn(session, 'getToken').and.returnValue(token);
  //   spyOn(session, 'clear');
  //   spyOn(session, 'authenticated').and.returnValue(true);
  //   spyOn(authenticator, 'refresh').and.returnValue(throwError(() => of(new HttpErrorResponse({}))));
  //   http.get('/api').subscribe();
  //   const request = httpMock.expectOne('/api');
  //   request.flush("", {status: 401, statusText: "JWT expired"});
  //   expect(session.clear).toHaveBeenCalledTimes(1);
  // }));
});
