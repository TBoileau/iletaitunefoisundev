import {Observable} from "rxjs";
import {HttpClient} from "@angular/common/http";
import {Inject, Injectable, InjectionToken, Provider} from "@angular/core";
import {tap} from "rxjs/operators";
import {SESSION_TOKEN, SessionInterface, Token} from "./session.service";
import {Player} from "../../adventure/entities/player";

@Injectable({
  providedIn: 'root'
})
export class Authenticator implements LoginInterface, RefreshTokenInterface {
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

export interface LoginInterface {
  login(credentials: Credentials): Observable<Token>;
}

export interface RefreshTokenInterface {
  refreshToken(token: Token): Observable<Token>;
}

export interface PlayerInterface {
  me: Observable<Player | null>;

  reset(): void;
}

export interface Credentials {
  email: string;
  password: string;
}

export const LOGIN_TOKEN = new InjectionToken<LoginInterface>('app.security.login');

export const REFRESH_TOKEN_TOKEN = new InjectionToken<RefreshTokenInterface>('app.security.refresh_token');

export const LOGIN_PROVIDER: Provider = {
  provide: LOGIN_TOKEN,
  useClass: Authenticator,
};

export const REFRESH_TOKEN_PROVIDER: Provider = {
  provide: REFRESH_TOKEN_TOKEN,
  useClass: Authenticator,
};
