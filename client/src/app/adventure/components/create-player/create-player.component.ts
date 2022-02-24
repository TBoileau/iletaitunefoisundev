import {Component, Inject} from '@angular/core';
import {Route, Router} from "@angular/router";
import {ControlsOf, FormControl, FormGroup} from "@ngneat/reactive-forms";
import {Validators} from "@angular/forms";
import {HttpErrorResponse} from "@angular/common/http";
import {CREATE_PLAYER_PROVIDER, CREATE_PLAYER_TOKEN, CreatePlayer, NewPlayer} from "./create-player.service";
import {AuthGuard} from "../../../core/guard/auth.guard";

@Component({
  selector: 'app-adventure-create-player',
  templateUrl: './create-player.component.html',
  styleUrls: ['./create-player.component.scss'],
  providers: [CREATE_PLAYER_PROVIDER]
})
export class CreatePlayerComponent {
  createPlayerForm = new FormGroup<ControlsOf<NewPlayer>>({
    name: new FormControl('', [Validators.required])
  });


  constructor(@Inject(CREATE_PLAYER_TOKEN) private createPlayer: CreatePlayer, private router: Router) {
  }

  onSubmit() {
    this.createPlayer.create(this.createPlayerForm.value).subscribe({
      next: () => {
        this.router.navigate(['/dashboard']);
      },
      error: (err: HttpErrorResponse) => {
        this.createPlayerForm.mergeErrors({violations: err.error.violations});
      }
    });

    return false;
  }
}

export const CREATE_PLAYER_ROUTE: Route = {
  path: 'create-player',
  component: CreatePlayerComponent,
  canActivate: [AuthGuard]
};
