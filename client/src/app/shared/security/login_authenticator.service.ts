import {Inject, Injectable} from "@angular/core";
import {Session, SESSION, Token} from "./session.service";
import {HttpClient, HttpErrorResponse} from "@angular/common/http";
import {tap} from "rxjs/operators";
import {Subject} from "rxjs";
import {FormLoginAuthenticatorInterface} from "./authenticator.service";

@Injectable({
  providedIn: 'root'
})
export class LoginAuthenticatorService implements FormLoginAuthenticatorInterface {
  onAuthentication: Subject<Token> = new Subject<Token>();

  constructor(private http: HttpClient, @Inject(SESSION) private session: Session) {
  }

  authenticate<Credentials>(credentials: Credentials): void {
    this.http.post<Token>('/api/security/login', credentials)
      .pipe(
        tap((token: Token): void => {
          this.session.setToken(token);
        })
      )
      .subscribe({
        next: (token: Token) => {
          this.onAuthentication.next(token);
        },
        error: (error: HttpErrorResponse) => {
          this.onAuthentication.error(error);
        }
      });
  }

  supports(): boolean {
    return !this.session.authenticated();
  }
}
