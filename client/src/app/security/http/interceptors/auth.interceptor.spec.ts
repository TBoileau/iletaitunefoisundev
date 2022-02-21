import {TestBed} from '@angular/core/testing';
import {HttpClient, HttpErrorResponse} from "@angular/common/http";
import {HttpClientTestingModule, HttpTestingController} from "@angular/common/http/testing";
import {AUTH_INTERCEPTOR_PROVIDER} from "./auth.interceptor";
import {SESSION_PROVIDER, SESSION_TOKEN, SessionInterface, Token} from "../../contracts/session";
import {STORAGE_MANAGER_PROVIDER} from "../../../shared/storage/storage-manager.service";
import {REFRESH_TOKEN_PROVIDER, REFRESH_TOKEN_TOKEN, RefreshToken} from "../../contracts/refresh-token";
import {RouterTestingModule} from "@angular/router/testing";
import {Router} from "@angular/router";
import {LOGIN_ROUTE} from "../../components/login/login.component";
import {of, throwError} from "rxjs";

describe('AuthInterceptor', () => {
  let httpMock: HttpTestingController;
  let session: SessionInterface;
  let http: HttpClient;
  let refreshToken: RefreshToken;
  let router: Router;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [
        HttpClientTestingModule,
        RouterTestingModule.withRoutes([LOGIN_ROUTE])
      ],
      providers: [AUTH_INTERCEPTOR_PROVIDER, SESSION_PROVIDER, STORAGE_MANAGER_PROVIDER, REFRESH_TOKEN_PROVIDER]
    });
    httpMock = TestBed.inject(HttpTestingController);
    http = TestBed.inject(HttpClient);
    router = TestBed.inject(Router);
    refreshToken = TestBed.inject(REFRESH_TOKEN_TOKEN);
    session = TestBed.inject(SESSION_TOKEN);
  });

  describe('when is authenticated', () => {
    beforeEach(() => {
      const token: Token = {
        token: 'token',
        refreshToken: 'refresh_token'
      };
      spyOn(session, 'getToken').and.returnValue(token);
      spyOn(session, 'authenticated').and.returnValue(true);
    });

    it('should not add bearer token to Authorization header', () => {
      http.get('/api/security/login').subscribe();
      const request = httpMock.expectOne('/api/security/login');
      expect(request.request.headers.get('Authorization')).toBeNull();
    });

    it('should add bearer token to Authorization header', () => {
      http.get('/api').subscribe();
      const request = httpMock.expectOne('/api');
      expect(request.request.headers.get('Authorization')).toBe('Bearer token');
    });

    it('should add bearer token after refresh to Authorization header', () => {
      const newToken: Token = {
        token: 'new_token',
        refreshToken: 'refresh_token'
      };
      spyOn(refreshToken, 'refreshToken').and.returnValue(of(newToken));
      http.get('/api').subscribe();
      const request = httpMock.expectOne('/api');
      request.flush("", {status: 401, statusText: "JWT expired"});
      expect(request.request.headers.get('Authorization')).toBe('Bearer token');
    });

    it('should clear session if refresh token has expired', () => {
      spyOn(router, 'navigate');
      spyOn(refreshToken, 'refreshToken').and.returnValue(throwError(() => new HttpErrorResponse({})));
      http.get('/api').subscribe();
      const request = httpMock.expectOne('/api');
      request.flush("", {status: 401, statusText: "JWT expired"});
      expect(router.navigate).toHaveBeenCalledWith(['/login']);
    });
  });

  it('should return 401', () => {
    spyOn(session, 'getToken').and.returnValue(null);
    spyOn(session, 'authenticated').and.returnValue(false);
    http.get('/api').subscribe();
    const request = httpMock.expectOne('/api');
    request.flush("", {status: 401, statusText: "JWT expired"});
    expect(request.request.headers.get('Authorization')).toBeNull();
  });
});
