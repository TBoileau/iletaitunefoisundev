import {TestBed} from '@angular/core/testing';
import {HttpClient} from "@angular/common/http";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {REGISTER, Register, RegisterInput, RegisterService} from "../../../app/security/register/register.service";

describe('Register', () => {
  let register: Register;
  let http: HttpClient;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [
        HttpClientTestingModule
      ],
      providers: [
        {provide: REGISTER, useClass: RegisterService}
      ]
    });
    register = TestBed.inject(REGISTER);
    http = TestBed.inject(HttpClient);
  });

  it('should be created', () => {
    expect(register).toBeTruthy();
  });

  it('should be registered', () => {
    const registerInput: RegisterInput = {
      email: 'user@email.com',
      plainPassword: 'Password123!',
    };
    spyOn(http, 'post').withArgs('/api/security/register', registerInput)
    register.execute(registerInput);
    expect(http.post).toHaveBeenCalledWith('/api/security/register', registerInput);
    expect(http.post).toHaveBeenCalledTimes(1);
  });
});
