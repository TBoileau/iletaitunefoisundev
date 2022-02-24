import {HTTP_INTERCEPTORS, HttpEvent, HttpHandler, HttpInterceptor, HttpRequest} from "@angular/common/http";
import {BehaviorSubject, EMPTY, Observable, of} from "rxjs";
import {Inject, Injectable, Provider} from "@angular/core";
import {catchError, filter, switchMap, take} from "rxjs/operators";
import {Router} from "@angular/router";
import {SESSION_TOKEN, SessionInterface, Token} from "../security/session.service";
import {REFRESH_TOKEN_TOKEN, RefreshTokenInterface} from "../security/authenticator.service";

@Injectable({
  providedIn: 'root'
})
export class AuthInterceptor implements HttpInterceptor {
  private isRefreshing = false;

  private tokenSubject: BehaviorSubject<any> = new BehaviorSubject<any>(null);

  constructor(
    private router: Router,
    @Inject(SESSION_TOKEN) private session: SessionInterface,
    @Inject(REFRESH_TOKEN_TOKEN) private refreshToken: RefreshTokenInterface,
  ) {
  }

  private static addTokenHeader(req: HttpRequest<any>, token: Token): HttpRequest<any> {
    return req.clone({
      setHeaders: {
        Authorization: `Bearer ${token.token}`
      }
    });
  }

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    if (req.url.includes('/api/security/login')) {
      return next.handle(req);
    }

    let authReq = req;

    if (this.session.authenticated()) {
      // @ts-ignore
      authReq = AuthInterceptor.addTokenHeader(authReq, this.session.getToken());
    }

    return next.handle(authReq).pipe(catchError(error => {
      if (error.status === 401 && !this.isRefreshing) {
        this.isRefreshing = true;
        this.tokenSubject.next(null);
        if (this.session.authenticated()) {
          // @ts-ignore
          return this.refreshToken.refreshToken(this.session.getToken()).pipe(
            switchMap((token: any) => {
              this.isRefreshing = false;
              this.tokenSubject.next(token);
              return next.handle(AuthInterceptor.addTokenHeader(req, token));
            }),
            catchError(() => {
              this.session.clear();
              this.isRefreshing = false;
              this.router.navigate(['/login'])
              return EMPTY;
            })
          );
        }

        return this.tokenSubject.pipe(
          filter(token => token !== null),
          take(1),
          switchMap(token => next.handle(AuthInterceptor.addTokenHeader(req, token)))
        );
      }

      return of(error);
    }));
  }
}

export const AUTH_INTERCEPTOR_PROVIDER: Provider = {
  provide: HTTP_INTERCEPTORS,
  useClass: AuthInterceptor,
  multi: true
};
