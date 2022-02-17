import {Inject, Injectable, InjectionToken} from "@angular/core";
import {Session, SESSION, Token} from "./session.service";
import {HttpClient, HttpErrorResponse} from "@angular/common/http";
import {tap} from "rxjs/operators";
import {ActivatedRoute, Router} from "@angular/router";
import {Observable} from "rxjs";

@Injectable({
  providedIn: 'root'
})
export class AuthenticatorService implements Authenticator {
  constructor(
    private http: HttpClient,
    private route: ActivatedRoute,
    private router: Router,
    @Inject(SESSION) private session: Session
  ) {
  }

  authenticate(credentials: Credentials, onFailure: (error: HttpErrorResponse) => void): void {
    this.http.post<Token>('/api/security/login', credentials)
      .pipe(
        tap((token: Token): void => {
          this.session.setToken(token);
        })
      )
      .subscribe({
        next: this.onAuthenticationSuccess.bind(this),
        error: onFailure
      });
  }

  refresh(): Observable<Token> {
    return this.http.post<Token>('/api/security/token-refresh', {refreshToken: this.session.getToken()?.refreshToken})
      .pipe(
        tap((token: Token): void => {
          this.session.setToken(token);
        })
      );
  }

  onAuthenticationSuccess(): void {
    const params = this.route.snapshot.queryParams;
    const referrer = params.hasOwnProperty('return') ? params['return'] : '/';
    this.router.navigate([referrer]);
  }

  getLoginUrl(): string {
    return '/login';
  }

  supports(): boolean {
    return !this.session.authenticated();
  }
}

export interface Authenticator {
  authenticate(credentials: Credentials, onFailure: (error: HttpErrorResponse) => void): void;

  refresh(): Observable<Token>;

  getLoginUrl(): string;

  onAuthenticationSuccess(): void;

  supports(): boolean;
}

export interface Credentials {
  email: string;
  password: string;
}

export const AUTHENTICATOR = new InjectionToken<Authenticator>('Authenticator');
