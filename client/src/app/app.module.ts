import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {AppRoutingModule} from './app-routing.module';
import {ReactiveFormsModule} from "@angular/forms";
import {HTTP_INTERCEPTORS, HttpClientModule} from "@angular/common/http";
import {AppComponent} from './app.component';
import {RegisterComponent} from "./security/register/register.component";
import {LoginComponent} from "./security/login/login.component";
import {ApiInterceptor} from "./shared/http/api_interceptor.service";
import {REGISTER, RegisterService} from "./security/register/register.service";
import {SESSION, SessionService} from "./shared/security/session.service";
import {STORAGE_MANAGER, StorageManagerService} from "./shared/storage/storage_manager.service";
import {TestComponent} from "./security/test/test.component";
import {AUTHENTICATOR, AuthenticatorService} from "./shared/security/authenticator.service";
import {AuthInterceptor} from "./shared/http/auth_interceptor.service";

@NgModule({
  declarations: [
    AppComponent,
    RegisterComponent,
    LoginComponent,
    TestComponent,
  ],
  imports: [
    HttpClientModule,
    ReactiveFormsModule,
    BrowserModule,
    AppRoutingModule
  ],
  providers: [
    {provide: HTTP_INTERCEPTORS, useClass: ApiInterceptor, multi: true},
    {provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true},
    {provide: REGISTER, useClass: RegisterService},
    {provide: STORAGE_MANAGER, useClass: StorageManagerService},
    {provide: AUTHENTICATOR, useClass: AuthenticatorService},
    {provide: SESSION, useClass: SessionService},
  ],
  bootstrap: [AppComponent]
})
export class AppModule {
}
