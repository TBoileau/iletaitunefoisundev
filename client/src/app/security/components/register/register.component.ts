import {Component, Inject} from '@angular/core';
import {Validators} from "@angular/forms";
import {ControlsOf, FormControl, FormGroup} from "@ngneat/reactive-forms";
import {HttpErrorResponse} from "@angular/common/http";
import {Route, Router} from "@angular/router";
import {Register, REGISTER_PROVIDER, REGISTER_TOKEN, Registration} from "../../contracts/register";
import {VisitorGuard} from "../../guard/visitor.guard";

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss'],
  providers: [REGISTER_PROVIDER]
})
export class RegisterComponent {
  registerForm = new FormGroup<ControlsOf<Registration>>({
    email: new FormControl('', [
      Validators.required,
      Validators.email
    ]),
    plainPassword: new FormControl('', [
      Validators.required,
      Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/)
    ])
  })

  constructor(@Inject(REGISTER_TOKEN) private register: Register, private router: Router) {
  }

  onSubmit() {
    this.register.register(this.registerForm.value).subscribe({
      next: () => {
        this.router.navigate(['/login']);
      },
      error: (err: HttpErrorResponse) => {
        this.registerForm.mergeErrors({violations: err.error.violations});
      }
    });

    return false;
  }
}

export const REGISTER_ROUTE: Route = {path: 'register', component: RegisterComponent, canActivate: [VisitorGuard]};
