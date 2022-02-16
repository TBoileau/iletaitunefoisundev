import {Component, Inject} from '@angular/core';
import {AbstractControl, FormControl, FormGroup, Validators} from "@angular/forms";
import {Registration} from "./registration";
import {REGISTER, Register} from "./register";
import {HttpErrorResponse} from "@angular/common/http";
import {Violation} from "../../shared/validator/violation";

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent {
  registration = <Registration>{
    email: '',
    plainPassword: ''
  }

  registerForm = new FormGroup({
    email: new FormControl(this.registration.email, [
      Validators.required,
      Validators.email
    ]),
    plainPassword: new FormControl(this.registration.plainPassword, [
      Validators.required,
      Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/)
    ])
  })

  constructor(@Inject(REGISTER) private register: Register) {
  }

  get controls(): { [key: string]: AbstractControl } {
    return this.registerForm.controls;
  }

  onSubmit() {
    this.registration = <Registration>this.registerForm.value;

    this.register.execute(this.registration).subscribe({
      next: user => {
        console.log('REGISTER', user)
      },
      error: (err: HttpErrorResponse) => {
        if (err.status === 422) {
          err.error.violations.forEach((violation: Violation) => {
            this.registerForm.get(violation.propertyPath)?.setErrors({
              custom: violation.message
            });
          });
        } else {
          this.registerForm.setErrors({
            custom: err.error.detail
          });
        }
      }
    });

    return false;
  }
}
