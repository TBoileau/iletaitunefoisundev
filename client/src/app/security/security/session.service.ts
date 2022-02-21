import {Inject, Injectable} from "@angular/core";
import {SessionInterface, Token} from "../contracts/session";
import {STORAGE_MANAGER, StorageManager} from "../../shared/storage/storage-manager.service";

@Injectable({
  providedIn: 'root'
})
export class Session implements SessionInterface {
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
