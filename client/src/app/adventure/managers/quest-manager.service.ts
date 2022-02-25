import {merge, Observable, Subject} from "rxjs";
import {Quest} from "../entities/quest";
import {Injectable, InjectionToken, Provider} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {shareReplay} from "rxjs/operators";
import {Region} from "../entities/region";

@Injectable({
  providedIn: 'root'
})
export class QuestManager implements QuestManagerInterface {
  quests!: Observable<Array<Quest>>;
  private infiniteStream: Observable<any> = new Subject<void>().asObservable();

  constructor(private http: HttpClient) {
  }

  getQuestsByRegion(region: Region): Observable<Array<Quest>> {
    if (!this.quests) {
      this.quests = merge(
        this.http.get<Quest | null>(`/api/adventure/regions/${region.id}/quests`),
        this.infiniteStream
      ).pipe(shareReplay(1));
    }

    return this.quests;
  }
}

export interface QuestManagerInterface {
  getQuestsByRegion(region: Region): Observable<Array<Quest>>;
}

export const QUEST_MANAGER_TOKEN = new InjectionToken<QuestManagerInterface>('adventure.manager.quest_manager');

export const QUEST_MANAGER_PROVIDER: Provider = {
  provide: QUEST_MANAGER_TOKEN,
  useClass: QuestManager,
};
