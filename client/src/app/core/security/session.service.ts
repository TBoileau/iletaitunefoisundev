import {Inject, Injectable, InjectionToken} from "@angular/core";
import {STORAGE_MANAGER, StorageManager} from "../storage/storage-manager.service";
import {BehaviorSubject} from "rxjs";

@Injectable({
  providedIn: 'root'
})
export class Session implements SessionInterface {
  authenticate: BehaviorSubject<boolean> = new BehaviorSubject<boolean>(false);

  constructor(@Inject(STORAGE_MANAGER) private storageManager: StorageManager) {
  }

  clear(): void {
    this.storageManager.remove('token');
    this.authenticate.next(false);
  }

  authenticated(): boolean {
    return this.getToken() !== null;
  }

  getToken(): Token | null {
    const token = this.storageManager.get('token');
    if (token === null) {
      return null;
    }

    return <Token>JSON.parse(token);
  }

  setToken(token: Token): void {
    this.storageManager.set('token', JSON.stringify(token));
    this.authenticate.next(true);
  }
}

export interface SessionInterface {
  authenticate: BehaviorSubject<boolean>;

  setToken(token: Token): void;

  getToken(): Token | null;

  authenticated(): boolean;

  clear(): void;
}

export interface Token {
  token: string;
  refreshToken: string;
}

export const SESSION_TOKEN = new InjectionToken<SessionInterface>('app.security.session');

export const SESSION_PROVIDER = {
  provide: SESSION_TOKEN,
  useClass: Session
};
