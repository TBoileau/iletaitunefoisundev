import {Inject, Injectable} from "@angular/core";
import {Session, SESSION, Token} from "./session.service";
import {HttpClient, HttpErrorResponse, HttpEvent} from "@angular/common/http";
import {catchError, tap} from "rxjs/operators";
import {Observable, ObservableInput, of} from "rxjs";
import {RefreshAuthenticatorInterface} from "./authenticator.service";

@Injectable({
  providedIn: 'root'
})
export class RefreshAuthenticatorService implements RefreshAuthenticatorInterface {
  constructor(private http: HttpClient, @Inject(SESSION) private session: Session) {
  }

  authenticate<RefreshToken>(refreshToken: RefreshToken): Observable<Token> {
    return this.http.post<Token>('/api/security/token-refresh', refreshToken)
      .pipe(
        tap((token: Token): void => {
          this.session.setToken(token);
        }),
        catchError((error: HttpErrorResponse): ObservableInput<any> => {
          this.session.clear();
          return of(error);
        })
      );
  }

  supports(): boolean {
    return this.session.authenticated();
  }
}
