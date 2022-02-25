import {merge, Observable, Subject} from "rxjs";
import {Injectable, InjectionToken, Provider} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {shareReplay} from "rxjs/operators";
import {Map} from "../entities/map";

@Injectable({
  providedIn: 'root'
})
export class RegionManager implements RegionManagerInterface {
  current!: number;
  map!: Observable<Map>;
  private infiniteStream: Observable<any> = new Subject<void>().asObservable();

  constructor(private http: HttpClient) {
  }

  getMapByRegion(region: number): Observable<Map> {
    if (!this.map || this.current !== region) {
      this.current = region;
      this.map = merge(
        this.http.get<Map>(`/api/adventure/regions/${region}/map`),
        this.infiniteStream
      ).pipe(shareReplay(1));
    }

    return this.map;
  }
}

export interface RegionManagerInterface {
  getMapByRegion(region: number): Observable<Map>;
}

export const REGION_MANAGER_TOKEN = new InjectionToken<RegionManagerInterface>('adventure.manager.quest_manager');

export const REGION_MANAGER_PROVIDER: Provider = {
  provide: REGION_MANAGER_TOKEN,
  useClass: RegionManager,
};
