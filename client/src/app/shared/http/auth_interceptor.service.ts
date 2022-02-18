import {HttpEvent, HttpHandler, HttpInterceptor, HttpRequest} from "@angular/common/http";
import {EMPTY, Observable, of} from "rxjs";
import {Inject, Injectable} from "@angular/core";
import {Session, SESSION, Token} from "../security/session.service";
import {catchError, switchMap} from "rxjs/operators";
import {RefreshAuthenticatorService} from "../security/refresh_authenticator.service";
import {RefreshToken} from "../security/authenticator.service";
import {Router} from "@angular/router";

@Injectable({
  providedIn: 'root'
})
export class AuthInterceptor implements HttpInterceptor {
  private isRefreshing = false;

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
          if (this.authenticator.supports()) {
            // @ts-ignore
            const refreshToken: RefreshToken = {refreshToken: this.session.getToken().refreshToken};
            this.authenticator.authenticate(refreshToken);
            return this.authenticator.authentication.pipe(
              switchMap((token: any) => {
                this.isRefreshing = false;
                return next.handle(AuthInterceptor.addTokenHeader(req, token));
              })
            );
          }
        }

        this.router.navigate(['/login'])

        return EMPTY;
      }

      return of(error);
    }));
  }
}
