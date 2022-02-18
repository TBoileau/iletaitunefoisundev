import {HttpErrorResponse, HttpEvent, HttpHandler, HttpInterceptor, HttpRequest} from "@angular/common/http";
import {BehaviorSubject, EMPTY, Observable, of} from "rxjs";
import {Inject, Injectable} from "@angular/core";
import {Session, SESSION, Token} from "../security/session.service";
import {catchError, filter, switchMap, take} from "rxjs/operators";
import {RefreshAuthenticatorService} from "../security/refresh_authenticator.service";
import {RefreshToken} from "../security/authenticator.service";
import {Router} from "@angular/router";

@Injectable({
  providedIn: 'root'
})
export class AuthInterceptor implements HttpInterceptor {
  private isRefreshing = false;

  private tokenSubject: BehaviorSubject<any> = new BehaviorSubject<any>(null);

  constructor(
    private router: Router,
    @Inject(SESSION) private session: Session,
    private authenticator: RefreshAuthenticatorService
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
      if (error.status === 401) {
        if (!this.isRefreshing) {
          this.isRefreshing = true;
          this.tokenSubject.next(null);
          if (this.authenticator.supports()) {
            // @ts-ignore
            const refreshToken: RefreshToken = {refreshToken: this.session.getToken().refreshToken};
            return this.authenticator.authenticate(refreshToken).pipe(
              switchMap((token: any) => {
                this.isRefreshing = false;
                this.tokenSubject.next(token);
                return next.handle(AuthInterceptor.addTokenHeader(req, token));
              }),
              catchError(() => {
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
      }

      return of(error);
    }));
  }
}
