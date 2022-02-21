import {Token} from "./session";
import {Observable} from "rxjs";

export interface AuthenticatorInterface {
  authenticate(observer: Observable<Token>): void;
}


