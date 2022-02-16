import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RegisterComponent } from './register.component';
import {Register, REGISTER} from "./register";
import {RegisterService} from "./register.service";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {ReactiveFormsModule} from "@angular/forms";
import {HttpClient} from "@angular/common/http";
import {Registration} from "./registration";
import {Observable, of} from "rxjs";
import {User} from "../models/user";

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
    const registration = <Registration>{
      email: 'user@email.com',
      plainPassword: 'Password123!',
    };
    component.registerForm.setValue(registration);
    spyOn(register, 'execute').withArgs(registration).and.returnValue(of(<User>{
      id: 1,
      email: 'user@email.com',
      forgottenPasswordToken: null
    }));
    component.onSubmit();
    expect(register.execute).toHaveBeenCalledWith(registration);
  });
});
