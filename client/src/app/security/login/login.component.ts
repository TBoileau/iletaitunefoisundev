import {Component, Inject} from '@angular/core';
import {Validators} from "@angular/forms";
import {ControlsOf, FormControl, FormGroup} from "@ngneat/reactive-forms";
import {HttpErrorResponse} from "@angular/common/http";
import {Authenticator, AUTHENTICATOR, Credentials} from "../../shared/security/authenticator.service";
import {Violation} from "../../shared/validator/violation";

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {
  loginForm = new FormGroup<ControlsOf<Credentials>>({
    email: new FormControl('', [
      Validators.required,
      Validators.email
    ]),
    password: new FormControl('', [Validators.required])
  })

  constructor(@Inject(AUTHENTICATOR) private authenticator: Authenticator) {
  }

  onSubmit() {
    this.authenticator.authenticate(
      this.loginForm.value,
      (error: HttpErrorResponse): void => {
        this.loginForm.mergeErrors({violations: [<Violation>{message: error.error.message}]});
      }
    );

    return false;
  }
}
