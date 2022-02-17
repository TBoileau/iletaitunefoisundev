import { ComponentFixture, TestBed } from '@angular/core/testing';
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {ReactiveFormsModule} from "@angular/forms";
import {of} from "rxjs";
import {RegisterComponent} from "../../../app/security/register/register.component";
import {
  REGISTER,
  Register,
  RegisterInput,
  RegisterOutput,
  RegisterService
} from "../../../app/security/register/register.service";
import {HttpErrorResponse} from "@angular/common/http";
import {Violation} from "../../../app/shared/validator/violation";

describe('RegisterComponent', () => {
  let component: RegisterComponent;
  let fixture: ComponentFixture<RegisterComponent>;
  let register: Register;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [
        ReactiveFormsModule,
        HttpClientTestingModule
      ],
      providers: [
        { provide: REGISTER, useClass: RegisterService }
      ],
      declarations: [ RegisterComponent ]
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
    const registerInput = <RegisterInput>{
      email: 'user@email.com',
      plainPassword: 'Password123!',
    };
    component.registerForm.setValue(registerInput);
    spyOn(register, 'execute').withArgs(registerInput).and.returnValue(of(<RegisterOutput>{
      id: 1,
      email: 'user@email.com',
      forgottenPasswordToken: null
    }));
    component.onSubmit();
    expect(register.execute).toHaveBeenCalledWith(registerInput);
  });
});
