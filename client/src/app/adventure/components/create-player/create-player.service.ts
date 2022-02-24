import {Observable} from "rxjs";
import {Player} from "../../entities/player";
import {Inject, Injectable, InjectionToken, Provider} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {PLAYER_MANAGER_TOKEN, PlayerManagerInterface} from "../../managers/player-manager.service";
import {tap} from "rxjs/operators";

@Injectable({
  providedIn: 'root'
})
export class CreatePlayer implements CreatePlayerInterface {
  constructor(private http: HttpClient, @Inject(PLAYER_MANAGER_TOKEN) private playerManager: PlayerManagerInterface) {
  }

  create(player: NewPlayer): Observable<Player> {
    return this.http.post<Player>('/api/adventure/players', player).pipe(tap(() => this.playerManager.reset()));
  }
}

export interface CreatePlayerInterface {
  create(player: NewPlayer): Observable<Player>;
}

export interface NewPlayer {
  name: string;
}

export const CREATE_PLAYER_TOKEN = new InjectionToken<CreatePlayer>('adventure.create_player');

export const CREATE_PLAYER_PROVIDER: Provider = {
  provide: CREATE_PLAYER_TOKEN,
  useClass: CreatePlayer
};
