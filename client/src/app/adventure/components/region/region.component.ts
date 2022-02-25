import {Component, Inject, OnInit} from '@angular/core';
import {ActivatedRoute, Route} from "@angular/router";
import {PlayerGuard} from "../../guard/player.guard";
import {AuthGuard} from "../../../core/guard/auth.guard";
import {Observable} from "rxjs";
import {Map} from "../../entities/map";
import {REGION_MANAGER_TOKEN, RegionManagerInterface} from "../../managers/region-manager.service";

@Component({
  selector: 'app-adventure-region',
  templateUrl: './region.component.html',
  styleUrls: ['./region.component.scss'],
})
export class RegionComponent implements OnInit {
  map: Observable<Map> = new Observable<Map>();

  constructor(private route: ActivatedRoute, @Inject(REGION_MANAGER_TOKEN) private questManager: RegionManagerInterface) {
  }

  ngOnInit(): void {
    this.map = this.questManager.getMapByRegion(+(this.route.snapshot.params['region']));
  }
}

export const REGION_ROUTE: Route = {
  path: 'regions/:region',
  component: RegionComponent,
  canActivate: [AuthGuard, PlayerGuard]
};
