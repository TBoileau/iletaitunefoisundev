import {Component, Inject, OnInit} from '@angular/core';
import {ActivatedRoute, Route} from "@angular/router";
import {PlayerGuard} from "../../guard/player.guard";
import {AuthGuard} from "../../../core/guard/auth.guard";
import {Observable, tap} from "rxjs";
import {WORLD_MANAGER_TOKEN, WorldManagerInterface} from "../../managers/world-manager.service";
import {map} from "rxjs/operators";
import {Continent} from "../../entities/continent";
import {World} from "../../entities/world";
import {Region} from "../../entities/region";
import {Quest} from "../../entities/quest";
import {Map} from "../../entities/map";
import {REGION_MANAGER_TOKEN, RegionManagerInterface} from "../../managers/region-manager.service";

@Component({
  selector: 'app-adventure-region',
  templateUrl: './region.component.html',
  styleUrls: ['./region.component.scss'],
})
export class RegionComponent implements OnInit{
  region: Observable<Region> = new Observable<Region>();
  continent: Observable<Continent> = new Observable<Continent>();
  world: Observable<World> = new Observable<World>();
  map: Observable<Map> = new Observable<Map>();

  constructor(
    private route: ActivatedRoute,
    @Inject(WORLD_MANAGER_TOKEN) private worldManager: WorldManagerInterface,
    @Inject(REGION_MANAGER_TOKEN) private questManager: RegionManagerInterface
  ) {
  }

  ngOnInit(): void {
    // @ts-ignore
    this.world = this.worldManager.getWorlds()
      .pipe(
        map(worlds => worlds.find(world => world.id === +(this.route.snapshot.params['world'])))
      );

    // @ts-ignore
    this.continent = this.world
      .pipe(
        // @ts-ignore
        map(world => world.continents.find(continent => continent.id === +(this.route.snapshot.params['continent']))),
      );

    // @ts-ignore
    this.region = this.continent
      .pipe(
        // @ts-ignore
        map(continent => continent.regions.find(region => region.id === +(this.route.snapshot.params['region']))),
        tap(region => this.map = this.questManager.getMapByRegion(region))
      );
  }
}

export const REGION_ROUTE: Route = {
  path: 'worlds/:world/continents/:continent/regions/:region',
  component: RegionComponent,
  canActivate: [AuthGuard, PlayerGuard]
};
