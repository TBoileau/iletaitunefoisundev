import {Component, Inject, OnInit} from '@angular/core';
import {Route} from "@angular/router";
import {PlayerGuard} from "../../guard/player.guard";
import {AuthGuard} from "../../../core/guard/auth.guard";
import {WORLD_MANAGER_PROVIDER, WORLD_MANAGER_TOKEN, WorldManagerInterface} from "../../managers/world-manager.service";
import {World} from "../../entities/world";
import {Observable} from "rxjs";

@Component({
  selector: 'app-adventure-worlds',
  templateUrl: './worlds.component.html',
  styleUrls: ['./worlds.component.scss'],
})
export class WorldsComponent implements OnInit{
  worlds: Observable<Array<World>> = new Observable<Array<World>>();

  constructor(@Inject(WORLD_MANAGER_TOKEN) private worldManager: WorldManagerInterface) {
  }

  ngOnInit(): void {
    this.worlds = this.worldManager.getWorlds();
  }
}

export const WORLDS_ROUTE: Route = {
  path: 'worlds',
  component: WorldsComponent,
  canActivate: [AuthGuard, PlayerGuard]
};
