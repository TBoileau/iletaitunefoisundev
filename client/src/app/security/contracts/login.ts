import {Observable} from "rxjs";
import {InjectionToken, Provider} from "@angular/core";
import {AuthConsumer} from "../consumers/auth-consumer.service";
import {Token} from "./session";

export interface Login {
  login(credentials: Credentials): Observable<Token>;
}

export interface Credentials {
  email: string;
  password: string;
}

export const LOGIN_TOKEN = new InjectionToken<Login>('security.contract.login');

export const LOGIN_PROVIDER: Provider = {
  provide: LOGIN_TOKEN,
  useClass: AuthConsumer,
};
