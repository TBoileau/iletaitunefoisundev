import {HttpEvent, HttpHandler, HttpInterceptor, HttpRequest} from "@angular/common/http";
import {BehaviorSubject, Observable} from "rxjs";
import {Inject, Injectable} from "@angular/core";
import {Session, SESSION, Token} from "../security/session.service";
import {Authenticator, AUTHENTICATOR} from "../security/authenticator.service";
import {catchError, filter, switchMap, take} from "rxjs/operators";

@Injectable({
  providedIn: 'root'
})
export class AuthInterceptor implements HttpInterceptor {
  private isRefreshing = false;
  private tokenSubject: BehaviorSubject<any> = new BehaviorSubject<any>(null);

  constructor(@Inject(SESSION) private session: Session, @Inject(AUTHENTICATOR) private authenticator: Authenticator) {
  }

  private static addTokenHeader(req: HttpRequest<any>, token: Token): HttpRequest<any> {
    return req.clone({
      setHeaders: {
        Authorization: `Bearer ${token.token}`
      }
    });
  }

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    let authReq = req;
    if (this.session.authenticated()) {
      // @ts-ignore
      authReq = AuthInterceptor.addTokenHeader(authReq, this.session.getToken());
    }

    return next.handle(authReq).pipe(catchError(error => {
      if (!authReq.url.includes('/api/security/login') && error.status === 401) {
        return this.handleJwtExpired(authReq, next);
      }
      return error;
    }));
  }

  private handleJwtExpired(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    if (!this.isRefreshing) {
      this.isRefreshing = true;
      this.tokenSubject.next(null);
      if (this.session.getToken()) {
        return this.authenticator.refresh().pipe(
          switchMap((token: Token) => {
            this.isRefreshing = false;
            this.tokenSubject.next(token);
            return next.handle(AuthInterceptor.addTokenHeader(req, token));
          }),
          catchError(error => {
            this.isRefreshing = false;
            this.session.clear();
            return error;
          })
        );
      }
    }
    return this.tokenSubject.pipe(
      filter(token => token !== null),
      take(1),
      switchMap((token: Token) => next.handle(AuthInterceptor.addTokenHeader(req, token)))
    );
  }
}
