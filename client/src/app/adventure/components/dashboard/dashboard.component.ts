import {Component, Inject} from '@angular/core';
import {Route} from "@angular/router";
import {PlayerGuard} from "../../guard/player.guard";
import {Observable} from "rxjs";
import {Player} from "../../entities/player";
import {PLAYER_MANAGER_TOKEN, PlayerManagerInterface} from "../../managers/player-manager.service";
import {AuthGuard} from "../../../core/guard/auth.guard";

@Component({
  selector: 'app-adventure-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss'],
})
export class DashboardComponent {
  player: Observable<Player | null> = this.playerManager.me;

  constructor(@Inject(PLAYER_MANAGER_TOKEN) private playerManager: PlayerManagerInterface) {
  }
}

export const DASHBOARD_ROUTE: Route = {
  path: 'dashboard',
  component: DashboardComponent,
  canActivate: [AuthGuard, PlayerGuard]
};
