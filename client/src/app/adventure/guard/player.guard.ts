import {ActivatedRouteSnapshot, CanActivate, Router, RouterStateSnapshot} from "@angular/router";
import {Inject, Injectable} from "@angular/core";
import {map} from "rxjs/operators";
import {Observable} from "rxjs";
import {PLAYER_MANAGER_TOKEN, PlayerManagerInterface} from "../managers/player-manager.service";

@Injectable({
  providedIn: 'root'
})
export class PlayerGuard implements CanActivate {
  constructor(private router: Router, @Inject(PLAYER_MANAGER_TOKEN) private playerManager: PlayerManagerInterface) {
  }

  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean> {
    return this.playerManager.me.pipe<boolean>(map(player => {
      if (player === null) {
        this.router.navigate(['/create-player']);
        return false;
      }

      return true;
    }));
  }
}
