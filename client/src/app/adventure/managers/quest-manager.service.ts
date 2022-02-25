import {HttpClient} from "@angular/common/http";
import {Inject, Injectable, InjectionToken, Provider} from "@angular/core";
import {Quest} from "../entities/quest";
import {Observable} from "rxjs";
import {Checkpoint} from "../entities/checkpoint";

@Injectable({
  providedIn: 'root'
})
export class QuestManager implements QuestManagerInterface {
  constructor(private http: HttpClient) {
  }

  getCheckpoint(quest: Quest): Observable<Checkpoint|null> {
    return this.http.get<Checkpoint|null>(`/api/adventure/quests/${quest.id}/checkpoint`);
  }

  start(quest: Quest): Observable<Checkpoint> {
    return this.http.post<Checkpoint>(`/api/adventure/quests/${quest.id}/start`, {});
  }

  finish(quest: Quest): Observable<Checkpoint> {
    return this.http.post<Checkpoint>(`/api/adventure/quests/${quest.id}/finish`, {});
  }
}

export interface QuestManagerInterface {
  getCheckpoint(quest: Quest): Observable<Checkpoint|null>;
  start(quest: Quest): Observable<Checkpoint>;
  finish(quest: Quest): Observable<Checkpoint>;
}

export const QUEST_MANAGER_TOKEN = new InjectionToken<QuestManagerInterface>('adventure.manager.quest_manager');

export const QUEST_MANAGER_PROVIDER: Provider = {
  provide: QUEST_MANAGER_TOKEN,
  useClass: QuestManager,
};
