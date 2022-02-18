import {Token} from "./session.service";
import {Observable, Subject} from "rxjs";
import {HttpEvent} from "@angular/common/http";

export interface RefreshAuthenticatorInterface extends Authenticator {
  authenticate<RefreshToken>(refreshToken: RefreshToken): Observable<Token>;
}

export interface FormLoginAuthenticatorInterface extends Authenticator {
  onAuthentication: Subject<Token>;

  authenticate<Credentials>(credentials: Credentials): void;
}

export interface Authenticator {
  supports(): boolean;

  authenticate<T>(data: T): void;
}

export interface RefreshToken {
  refreshToken: string;
}

export interface Credentials {
  email: string;
  password: string;
}
