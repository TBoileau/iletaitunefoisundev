import {ActivatedRouteSnapshot, CanActivate, Router, RouterStateSnapshot} from "@angular/router";
import {Inject, Injectable} from "@angular/core";
import {AUTHENTICATOR, Authenticator} from "./authenticator.service";

@Injectable({
  providedIn: 'root'
})
export class GuardService implements CanActivate {
  constructor(private router: Router, @Inject(AUTHENTICATOR) private authenticator: Authenticator) {
  }

  canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
    if (!this.authenticator.supports()) {
      return true;
    }

    this.router.navigate([this.authenticator.getLoginUrl()], {
      queryParams: {
        return: state.url
      }
    });
    return false;
  }
}
