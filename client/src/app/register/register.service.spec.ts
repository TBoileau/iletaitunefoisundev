import {TestBed} from '@angular/core/testing';
import {RegisterService} from './register.service';
import {REGISTER, Register} from "./register";
import {HttpClient} from "@angular/common/http";
import {Registration} from "./registration";
import {HttpClientTestingModule} from "@angular/common/http/testing";

describe('Register', () => {
  let register: Register;
  let http: HttpClient;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [
        HttpClientTestingModule
      ],
      providers: [
        { provide: REGISTER, useClass: RegisterService }
      ]
    });
    register = TestBed.inject(REGISTER);
    http = TestBed.inject(HttpClient);
  });

  it('should be created', () => {
    expect(register).toBeTruthy();
  });

  it('should be registered', () => {
    const registration = <Registration>{
      email: 'user@email.com',
      plainPassword: 'Password123!',
    };
    spyOn(http, 'post').withArgs('/api/security/register', registration)
    register.execute(registration);
    expect(http.post).toHaveBeenCalledWith('/api/security/register', registration);
    expect(http.post).toHaveBeenCalledTimes(1);
  });
});
