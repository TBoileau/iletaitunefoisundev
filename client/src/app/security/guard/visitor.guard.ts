import {ActivatedRouteSnapshot, CanActivate, Router, RouterStateSnapshot} from "@angular/router";
import {Inject, Injectable} from "@angular/core";
import {SESSION_TOKEN, SessionInterface} from "../contracts/session";

@Injectable({
  providedIn: 'root'
})
export class VisitorGuard implements CanActivate {
  constructor(private router: Router, @Inject(SESSION_TOKEN) private session: SessionInterface) {
  }

  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
    if (this.session.authenticated()) {
      this.router.navigate(['/']);
      return false;
    }

    return true;
  }
}
