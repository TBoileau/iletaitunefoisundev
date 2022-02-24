import {Component, Inject, OnInit} from '@angular/core';
import {ActivatedRoute, Route} from "@angular/router";
import {PlayerGuard} from "../../guard/player.guard";
import {AuthGuard} from "../../../core/guard/auth.guard";
import {Observable} from "rxjs";
import {WORLD_MANAGER_TOKEN, WorldManagerInterface} from "../../managers/world-manager.service";
import {map} from "rxjs/operators";
import {Continent} from "../../entities/continent";
import {World} from "../../entities/world";

@Component({
  selector: 'app-adventure-continent',
  templateUrl: './continent.component.html',
  styleUrls: ['./continent.component.scss'],
})
export class ContinentComponent implements OnInit{
  continent: Observable<Continent> = new Observable<Continent>();
  world: Observable<World> = new Observable<World>();

  constructor(private route: ActivatedRoute, @Inject(WORLD_MANAGER_TOKEN) private worldManager: WorldManagerInterface) {
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
  }
}

export const CONTINENT_ROUTE: Route = {
  path: 'worlds/:world/continents/:continent',
  component: ContinentComponent,
  canActivate: [AuthGuard, PlayerGuard]
};
