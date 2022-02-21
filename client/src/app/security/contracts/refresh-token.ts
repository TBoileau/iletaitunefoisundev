import {Observable} from "rxjs";
import {InjectionToken, Provider} from "@angular/core";
import {AuthConsumer} from "../consumers/auth-consumer.service";
import {Token} from "./session";

export interface RefreshToken {
  refreshToken(token: Token): Observable<Token>;
}

export const REFRESH_TOKEN_TOKEN = new InjectionToken<RefreshToken>('security.contract.refresh-token');

export const REFRESH_TOKEN_PROVIDER: Provider = {
  provide: REFRESH_TOKEN_TOKEN,
  useClass: AuthConsumer,
};
