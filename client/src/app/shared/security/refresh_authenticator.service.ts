import {Inject, Injectable} from "@angular/core";
import {Session, SESSION, Token} from "./session.service";
import {HttpClient, HttpEvent} from "@angular/common/http";
import {catchError, tap} from "rxjs/operators";
import {Observable} from "rxjs";
import {RefreshAuthenticatorInterface} from "./authenticator.service";

@Injectable({
  providedIn: 'root'
})
export class RefreshAuthenticatorService implements RefreshAuthenticatorInterface {
  public authentication: Observable<HttpEvent<any>> = new Observable<HttpEvent<any>>();

  constructor(private http: HttpClient, @Inject(SESSION) private session: Session) {
  }

  authenticate<RefreshToken>(refreshToken: RefreshToken): void {
    this.authentication = this.http.post<Token>('/api/security/token-refresh', refreshToken)
      .pipe(
        tap((token: Token): void => {
          this.session.setToken(token);
        }),
        catchError(() => {
          this.session.clear();
        })
      );
  }

  supports(): boolean {
    return this.session.authenticated();
  }
}
