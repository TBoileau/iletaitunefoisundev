import {Token} from "./session.service";
import {Subject} from "rxjs";

export interface RefreshAuthenticatorInterface extends Authenticator {
  authenticate<RefreshToken>(refreshToken: RefreshToken): void;
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
