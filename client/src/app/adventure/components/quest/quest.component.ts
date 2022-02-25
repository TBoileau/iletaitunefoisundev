import {Component, Inject, OnInit} from '@angular/core';
import {ActivatedRoute, Route} from "@angular/router";
import {PlayerGuard} from "../../guard/player.guard";
import {AuthGuard} from "../../../core/guard/auth.guard";
import {REGION_MANAGER_TOKEN, RegionManagerInterface} from "../../managers/region-manager.service";
import {BehaviorSubject, Observable, of} from "rxjs";
import {Map} from "../../entities/map";
import {Quest} from "../../entities/quest";
import {tap} from "rxjs/operators";
import {QUEST_MANAGER_TOKEN, QuestManagerInterface} from "../../managers/quest-manager.service";
import {Checkpoint} from "../../entities/checkpoint";

@Component({
  selector: 'app-adventure-region',
  templateUrl: './quest.component.html',
  styleUrls: ['./quest.component.scss'],
})
export class QuestComponent implements OnInit {
  checkpoint = new BehaviorSubject<Checkpoint|null>(null);
  map: Observable<Map> = new Observable<Map>();
  quest: Observable<Quest> = new Observable<Quest>();

  constructor(
    private route: ActivatedRoute,
    @Inject(REGION_MANAGER_TOKEN) private regionManager: RegionManagerInterface,
    @Inject(QUEST_MANAGER_TOKEN) private questManager: QuestManagerInterface
  ) {
  }

  ngOnInit(): void {
    this.map = this.regionManager.getMapByRegion(+(this.route.snapshot.params['region'])).pipe(tap(map => {
      const quest = map.quests[this.route.snapshot.params['quest']];
      this.quest = of(quest);
      this.questManager.getCheckpoint(quest).subscribe(checkpoint => this.checkpoint.next(checkpoint));
    }));
  }

  finish() {
    this.quest.subscribe(quest => {
      this.questManager.finish(quest).subscribe(checkpoint => this.checkpoint.next(checkpoint));
    });
  }

  start() {
    this.quest.subscribe(quest => {
      this.questManager.start(quest).subscribe(checkpoint => this.checkpoint.next(checkpoint));
    });
  }
}

export const QUEST_ROUTE: Route = {
  path: 'regions/:region/quests/:quest',
  component: QuestComponent,
  canActivate: [AuthGuard, PlayerGuard]
};
