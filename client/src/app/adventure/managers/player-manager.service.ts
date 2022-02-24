import {Player} from "../entities/player";
import {merge, Observable, Subject} from "rxjs";
import {HttpClient} from "@angular/common/http";
import {shareReplay} from "rxjs/operators";
import {Inject, Injectable, InjectionToken, Provider} from "@angular/core";
import {SESSION_TOKEN, SessionInterface} from "../../core/security/session.service";

@Injectable({
  providedIn: 'root'
})
export class PlayerManager implements PlayerManagerInterface {
  me!: Observable<Player | null>;
  private infiniteStream: Observable<any> = new Subject<void>().asObservable();

  constructor(private http: HttpClient, @Inject(SESSION_TOKEN) session: SessionInterface) {
    session.authenticate.subscribe(() => this.reset());
  }

  reset(): void {
    this.me = merge(
      this.http.get<Player | null>('/api/adventure/players/me'),
      this.infiniteStream
    ).pipe(shareReplay(1));
  }
}

export interface PlayerManagerInterface {
  me: Observable<Player | null>;

  reset(): void;
}

export const PLAYER_MANAGER_TOKEN = new InjectionToken<PlayerManagerInterface>('adventure.manager.player_manager');

export const PLAYER_MANAGER_PROVIDER: Provider = {
  provide: PLAYER_MANAGER_TOKEN,
  useClass: PlayerManager,
};
