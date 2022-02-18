import {Inject, Injectable, InjectionToken} from "@angular/core";
import {STORAGE_MANAGER, StorageManager} from "../storage/storage_manager.service";

@Injectable({
  providedIn: 'root'
})
export class SessionService implements Session {
  constructor(@Inject(STORAGE_MANAGER) private storageManager: StorageManager) {
  }

  clear(): void {
    this.storageManager.remove('token');
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
  }
}

export interface Session {
  setToken(token: Token): void;

  authenticated(): boolean;

  clear(): void;

  getToken(): Token | null;
}

export interface Token {
  token: string;
  refreshToken: string;
}

export const SESSION = new InjectionToken<Session>('Session');
