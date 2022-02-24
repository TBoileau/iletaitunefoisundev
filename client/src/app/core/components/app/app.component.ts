import {Component, Inject} from '@angular/core';
import {Router} from "@angular/router";
import {SESSION_TOKEN, SessionInterface} from "../../security/session.service";

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'client';

  constructor(@Inject(SESSION_TOKEN) public session: SessionInterface, private router: Router) {
  }

  logout() {
    this.session.clear();
    this.router.navigate(['/']);
  }
}
