import {ComponentFixture, TestBed} from '@angular/core/testing';
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {ReactiveFormsModule} from "@angular/forms";
import {of, throwError} from "rxjs";
import {LoginComponent} from "../../../app/security/login/login.component";
import {HttpClient, HttpErrorResponse} from "@angular/common/http";
import {RouterTestingModule} from "@angular/router/testing";
import {Authenticator, Credentials} from "../../../app/shared/security/authenticator.service";
import {SESSION, SessionService, Token} from "../../../app/shared/security/session.service";
import {STORAGE_MANAGER, StorageManagerService} from "../../../app/shared/storage/storage_manager.service";
import {Router} from "@angular/router";
import {LoginAuthenticatorService} from "../../../app/shared/security/login_authenticator.service";

describe('LoginComponent', () => {
  let component: LoginComponent;
  let fixture: ComponentFixture<LoginComponent>;
  let authenticator: LoginAuthenticatorService;
  let http: HttpClient;
  let router: Router;

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
        {provide: LoginAuthenticatorService},
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
    authenticator = TestBed.inject(LoginAuthenticatorService);
    http = TestBed.inject(HttpClient);
    router = TestBed.inject(Router);
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
    spyOn(router, 'navigate');
    component.onSubmit();
    expect(router.navigate).toHaveBeenCalledWith(['/']);
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
