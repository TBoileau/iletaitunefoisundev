import {Credentials, Login} from "../contracts/login";
import {Observable} from "rxjs";
import {HttpClient} from "@angular/common/http";
import {Inject, Injectable} from "@angular/core";
import {SESSION_TOKEN, SessionInterface, Token} from "../contracts/session";
import {tap} from "rxjs/operators";
import {RefreshToken} from "../contracts/refresh-token";

@Injectable({
  providedIn: 'root'
})
export class AuthConsumer implements Login, RefreshToken {
  constructor(private http: HttpClient, @Inject(SESSION_TOKEN) private session: SessionInterface) {
  }

  refreshToken(token: Token): Observable<Token> {
    return this.http.post<Token>('/api/security/token-refresh', {refreshToken: token.refreshToken}).pipe(
      tap((token: Token) => {
        this.session.setToken(token);
      })
    );
  }

  login(credentials: Credentials): Observable<Token> {
    return this.http.post<Token>('/api/security/login', credentials).pipe(
      tap((token: Token) => {
        this.session.setToken(token);
      })
    );
  }
}
