import {InjectionToken} from "@angular/core";
import {Session} from "../security/session.service";

export interface SessionInterface {
  setToken(token: Token): void;

  getToken(): Token | null;

  authenticated(): boolean;

  clear(): void;
}

export interface Token {
  token: string;
  refreshToken: string;
}

export const SESSION_TOKEN = new InjectionToken<SessionInterface>('security.contract.session');

export const SESSION_PROVIDER = {
  provide: SESSION_TOKEN,
  useClass: Session
};
