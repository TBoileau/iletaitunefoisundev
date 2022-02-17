import {ComponentFixture, TestBed} from '@angular/core/testing';
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {ReactiveFormsModule} from "@angular/forms";
import {of, throwError} from "rxjs";
import {LoginComponent} from "../../../app/security/login/login.component";
import {HttpClient, HttpErrorResponse} from "@angular/common/http";
import {RouterTestingModule} from "@angular/router/testing";
import {
  AUTHENTICATOR,
  Authenticator,
  AuthenticatorService,
  Credentials
} from "../../../app/shared/security/authenticator.service";
import {SESSION, SessionService, Token} from "../../../app/shared/security/session.service";
import {STORAGE_MANAGER, StorageManagerService} from "../../../app/shared/storage/storage_manager.service";

describe('LoginComponent', () => {
  let component: LoginComponent;
  let fixture: ComponentFixture<LoginComponent>;
  let authenticator: Authenticator;
  let http: HttpClient;

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
        {provide: AUTHENTICATOR, useClass: AuthenticatorService},
        {provide: SESSION, useClass: SessionService},
        {provide: STORAGE_MANAGER, useClass: StorageManagerService},
      ],
      declarations: [LoginComponent]
    })
      .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(LoginComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
    authenticator = TestBed.inject(AUTHENTICATOR);
    http = TestBed.inject(HttpClient);
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should authenticate', () => {
    const credentials: Credentials = {
      email: 'user@email.com',
      password: 'Password123!',
    };
    component.loginForm.setValue(credentials);
    const token: Token = {
      token: 'token',
      refreshToken: 'refreshToken'
    }
    spyOn(http, 'post').withArgs('/api/security/login', credentials).and.returnValue(of(token));
    spyOn(authenticator, 'onAuthenticationSuccess');
    component.onSubmit();
    expect(authenticator.onAuthenticationSuccess).toHaveBeenCalled();
  });

  it('should raise an error', () => {
    const credentials: Credentials = {
      email: 'user@email.com',
      password: 'Password123!',
    };
    component.loginForm.setValue(credentials);
    spyOn(component.loginForm, 'mergeErrors');
    spyOn(http, 'post')
      .withArgs('/api/security/login', credentials)
      .and.returnValue(throwError(() => new HttpErrorResponse({
        error: {
          message: 'Invalid credentials'
        }
      })));
    component.onSubmit();
    expect(component.loginForm.mergeErrors).toHaveBeenCalled();
  });

});
