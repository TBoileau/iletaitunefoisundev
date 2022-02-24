import {merge, Observable, Subject} from "rxjs";
import {World} from "../entities/world";
import {Injectable, InjectionToken, Provider} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {shareReplay} from "rxjs/operators";
import {Player} from "../entities/player";

@Injectable({
  providedIn: 'root'
})
export class WorldManager implements WorldManagerInterface {
  worlds!: Observable<Array<World>>;
  private infiniteStream: Observable<any> = new Subject<void>().asObservable();

  constructor(private http: HttpClient) {
  }

  getWorlds(): Observable<Array<World>> {
    if (!this.worlds) {
      this.worlds = merge(
        this.http.get<Player | null>('/api/adventure/worlds'),
        this.infiniteStream
      ).pipe(shareReplay(1));
    }

    return this.worlds;
  }
}

export interface WorldManagerInterface {
  getWorlds(): Observable<Array<World>>;
}

export const WORLD_MANAGER_TOKEN = new InjectionToken<WorldManagerInterface>('adventure.manager.world_manager');

export const WORLD_MANAGER_PROVIDER: Provider = {
  provide: WORLD_MANAGER_TOKEN,
  useClass: WorldManager,
};
