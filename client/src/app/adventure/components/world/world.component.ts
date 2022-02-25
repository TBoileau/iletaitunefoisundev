import {Component, Inject, OnInit} from '@angular/core';
import {ActivatedRoute, Route} from "@angular/router";
import {PlayerGuard} from "../../guard/player.guard";
import {AuthGuard} from "../../../core/guard/auth.guard";
import {WORLD_MANAGER_TOKEN, WorldManagerInterface} from "../../managers/world-manager.service";
import {World} from "../../entities/world";
import {map} from "rxjs/operators";
import {Observable} from "rxjs";

@Component({
  selector: 'app-adventure-world',
  templateUrl: './world.component.html',
  styleUrls: ['./world.component.scss'],
})
export class WorldComponent implements OnInit {
  world: Observable<World> = new Observable<World>();

  constructor(private route: ActivatedRoute, @Inject(WORLD_MANAGER_TOKEN) private worldManager: WorldManagerInterface) {
  }

  ngOnInit(): void {
    // @ts-ignore
    this.world = this.worldManager.getWorlds()
      .pipe(
        map(worlds => worlds.find(world => world.id === +(this.route.snapshot.params['world'])))
      );
  }
}

export const WORLD_ROUTE: Route = {
  path: 'worlds/:world',
  component: WorldComponent,
  canActivate: [AuthGuard, PlayerGuard]
};
