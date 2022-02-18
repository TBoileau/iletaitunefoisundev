import {Component, OnInit} from '@angular/core';
import {Validators} from "@angular/forms";
import {ControlsOf, FormControl, FormGroup} from "@ngneat/reactive-forms";
import {HttpErrorResponse} from "@angular/common/http";
import {Credentials} from "../../shared/security/authenticator.service";
import {Violation} from "../../shared/validator/violation";
import {ActivatedRoute, Router} from "@angular/router";
import {LoginAuthenticatorService} from "../../shared/security/login_authenticator.service";

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  loginForm = new FormGroup<ControlsOf<Credentials>>({
    email: new FormControl('', [
      Validators.required,
      Validators.email
    ]),
    password: new FormControl('', [Validators.required])
  })

  constructor(
    private authenticator: LoginAuthenticatorService,
    private route: ActivatedRoute,
    private router: Router
  ) {
  }

  ngOnInit(): void {
    this.authenticator.onAuthentication.subscribe({
      next: () => {
        const params = this.route.snapshot.queryParams;
        const referrer = params.hasOwnProperty('return') ? params['return'] : '/';
        this.router.navigate([referrer]);
      },
      error: (error: HttpErrorResponse) => {
        this.loginForm.mergeErrors({violations: [<Violation>{message: error.error.message}]});
      }
    });
  }

  onSubmit() {
    this.authenticator.authenticate(this.loginForm.value);

    return false;
  }
}
