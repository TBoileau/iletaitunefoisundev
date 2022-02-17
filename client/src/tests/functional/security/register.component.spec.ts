import {ComponentFixture, TestBed} from '@angular/core/testing';
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {ReactiveFormsModule} from "@angular/forms";
import {of, throwError} from "rxjs";
import {RegisterComponent} from "../../../app/security/register/register.component";
import {
  REGISTER,
  Register,
  RegisterInput,
  RegisterOutput,
  RegisterService
} from "../../../app/security/register/register.service";
import {HttpErrorResponse} from "@angular/common/http";
import {RouterTestingModule} from "@angular/router/testing";
import {LoginComponent} from "../../../app/security/login/login.component";

describe('RegisterComponent', () => {
  let component: RegisterComponent;
  let fixture: ComponentFixture<RegisterComponent>;
  let register: Register;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        RouterTestingModule.withRoutes([
          {path: 'login', component: LoginComponent},
        ]),
        ReactiveFormsModule,
        HttpClientTestingModule
      ],
      providers: [
        {provide: REGISTER, useClass: RegisterService}
      ],
      declarations: [RegisterComponent]
    })
      .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(RegisterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
    register = TestBed.inject(REGISTER);
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should submit data', () => {
    const registerInput: RegisterInput = {
      email: 'user@email.com',
      plainPassword: 'Password123!',
    };
    component.registerForm.setValue(registerInput);
    const registerOutput: RegisterOutput = {
      id: 1,
      email: 'user@email.com',
      forgottenPasswordToken: null
    }
    spyOn(register, 'execute').withArgs(registerInput).and.returnValue(of(registerOutput));
    component.onSubmit();
    expect(register.execute).toHaveBeenCalledWith(registerInput);
  });

  it('should submit data raise an unprocessable entity error', () => {
    const registerInput: RegisterInput = {
      email: 'user@email.com',
      plainPassword: 'Password123!',
    };
    component.registerForm.setValue(registerInput);
    spyOn(component.registerForm, 'mergeErrors');
    spyOn(register, 'execute')
      .withArgs(registerInput)
      .and.returnValue(throwError(() => new HttpErrorResponse({
        error: {
          error: {
            violations: [
              {propertyPath: 'email', message: ''}
            ]
          }
        }
      })));
    component.onSubmit();
    expect(register.execute).toHaveBeenCalledWith(registerInput);
    expect(component.registerForm.mergeErrors).toHaveBeenCalled();
  });
});
