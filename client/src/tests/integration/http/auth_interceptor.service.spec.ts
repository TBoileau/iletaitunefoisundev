import {inject, TestBed} from '@angular/core/testing';
import {HTTP_INTERCEPTORS, HttpClient, HttpErrorResponse} from "@angular/common/http";
import {HttpClientTestingModule, HttpTestingController} from "@angular/common/http/testing";
import {SESSION, Session, SessionService, Token} from "../../../app/shared/security/session.service";
import {STORAGE_MANAGER, StorageManagerService} from "../../../app/shared/storage/storage_manager.service";
import {Authenticator, AUTHENTICATOR, AuthenticatorService} from "../../../app/shared/security/authenticator.service";
import {AuthInterceptor} from "../../../app/shared/http/auth_interceptor.service";
import {RouterTestingModule} from "@angular/router/testing";
import {of, throwError} from "rxjs";

describe('AuthInterceptor', () => {
  let httpMock: HttpTestingController;
  let session: Session;
  let authenticator: Authenticator;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [
        HttpClientTestingModule,
        RouterTestingModule
      ],
      providers: [
        {provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true},
        {provide: STORAGE_MANAGER, useClass: StorageManagerService},
        {provide: AUTHENTICATOR, useClass: AuthenticatorService},
        {provide: SESSION, useClass: SessionService},
      ]
    });
    httpMock = TestBed.inject(HttpTestingController);
    session = TestBed.inject(SESSION);
    authenticator = TestBed.inject(AUTHENTICATOR);
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
    spyOn(session, 'authenticated').and.returnValue(true);
    spyOn(authenticator, 'refresh').and.returnValue(of(token));
    http.get('/api').subscribe();
    const request = httpMock.expectOne('/api');
    request.flush("", {status: 401, statusText: "JWT expired"});
    expect(request.request.headers.get('Authorization')).toBe('Bearer token');
  }));

  it('should clear session if refresh token has expired', inject([HttpClient], (http: HttpClient) => {
    const token: Token = {
      token: 'token',
      refreshToken: 'refresh_token'
    };
    spyOn(session, 'getToken').and.returnValue(token);
    spyOn(session, 'clear');
    spyOn(session, 'authenticated').and.returnValue(true);
    spyOn(authenticator, 'refresh').and.returnValue(throwError(() => of(new HttpErrorResponse({}))));
    http.get('/api').subscribe();
    const request = httpMock.expectOne('/api');
    request.flush("", {status: 401, statusText: "JWT expired"});
    expect(session.clear).toHaveBeenCalledTimes(1);
  }));
});
