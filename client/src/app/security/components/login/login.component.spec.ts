import {createRoutingFactory, SpectatorRouting, SpyObject} from "@ngneat/spectator";
import {LoginComponent} from "./login.component";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {RouterTestingModule} from "@angular/router/testing";
import {ReactiveFormsModule} from "@angular/forms";
import {of, throwError} from "rxjs";
import {Router} from "@angular/router";
import {HttpErrorResponse} from "@angular/common/http";
import {Authenticator, Credentials, LOGIN_TOKEN, LoginInterface} from "../../../core/security/authenticator.service";
import {SESSION_PROVIDER, Token} from "../../../core/security/session.service";
import {STORAGE_MANAGER_PROVIDER} from "../../../core/storage/storage-manager.service";

describe('Login component', () => {
  let spectator: SpectatorRouting<LoginComponent>;
  let login: SpyObject<LoginInterface>;

  const createComponent = createRoutingFactory({
    component: LoginComponent,
    componentMocks: [Authenticator],
    componentProviders: [
      SESSION_PROVIDER,
      {provide: LOGIN_TOKEN, useExisting: Authenticator},
      STORAGE_MANAGER_PROVIDER
    ],
    imports: [HttpClientTestingModule, RouterTestingModule, ReactiveFormsModule]
  });

  beforeEach(() => {
    spectator = createComponent();
    login = spectator.inject(Authenticator, true);
  });

  describe('when login is successful', () => {
    let router: SpyObject<Router>;

    beforeEach(() => {
      router = spectator.inject(Router, true);
      const token: Token = {
        token: 'token',
        refreshToken: 'token'
      };
      login.login.and.returnValue(of(token));
    });

    it('should submit form login, create session and redirect to home', () => {
      spectator.typeInElement('user@email.com', '.input-email');
      spectator.typeInElement('Password123!', '.input-password');
      spectator.click('.form-submit');
      const credentials: Credentials = {
        email: 'user@email.com',
        password: 'Password123!'
      };
      expect(login.login).toHaveBeenCalledWith(credentials);
      expect(router.navigate).toHaveBeenCalledWith(['/']);
    });

    it('should submit form login, create session and redirect to specific page', () => {
      spectator.setRouteQueryParam('return', '/foo');
      spectator.typeInElement('user@email.com', '.input-email');
      spectator.typeInElement('Password123!', '.input-password');
      spectator.click('.form-submit');
      const credentials: Credentials = {
        email: 'user@email.com',
        password: 'Password123!'
      };
      expect(login.login).toHaveBeenCalledWith(credentials);
      expect(router.navigate).toHaveBeenCalledWith(['/foo']);
    });
  });

  it('should submit form login and raise violations', () => {
    spyOn(spectator.component.loginForm, 'mergeErrors');
    const login = spectator.inject(Authenticator, true);
    login.login.and.returnValue(throwError(() => new HttpErrorResponse({
      error: {
        message: 'Invalid credentials'
      }
    })));
    spectator.typeInElement('user@email.com', '.input-email');
    spectator.typeInElement('Password123!', '.input-password');
    spectator.click('.form-submit');
    const credentials: Credentials = {
      email: 'user@email.com',
      password: 'Password123!'
    };
    expect(login.login).toHaveBeenCalledWith(credentials);
    expect(spectator.component.loginForm.mergeErrors).toHaveBeenCalled();
  });
});
