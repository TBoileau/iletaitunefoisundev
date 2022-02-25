import {Component, Inject, OnInit} from '@angular/core';
import {ActivatedRoute, Route} from "@angular/router";
import {PlayerGuard} from "../../guard/player.guard";
import {AuthGuard} from "../../../core/guard/auth.guard";
import {REGION_MANAGER_TOKEN, RegionManagerInterface} from "../../managers/region-manager.service";
import {Observable, of} from "rxjs";
import {Map} from "../../entities/map";
import {Quest} from "../../entities/quest";
import {tap} from "rxjs/operators";

@Component({
  selector: 'app-adventure-region',
  templateUrl: './quest.component.html',
  styleUrls: ['./quest.component.scss'],
})
export class QuestComponent implements OnInit {
  map: Observable<Map> = new Observable<Map>();
  quest: Observable<Quest> = new Observable<Quest>();

  constructor(private route: ActivatedRoute, @Inject(REGION_MANAGER_TOKEN) private questManager: RegionManagerInterface) {
  }

  ngOnInit(): void {
    this.map = this.questManager.getMapByRegion(+(this.route.snapshot.params['region'])).pipe(tap(map => {
      this.quest = of(map.quests[this.route.snapshot.params['quest']]);
    }));
  }
}

export const QUEST_ROUTE: Route = {
  path: 'regions/:region/quests/:quest',
  component: QuestComponent,
  canActivate: [AuthGuard, PlayerGuard]
};
