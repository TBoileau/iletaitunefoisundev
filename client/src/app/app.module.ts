import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './app.component';
import {ReactiveFormsModule} from "@angular/forms";
import {HTTP_INTERCEPTORS, HttpClientModule} from "@angular/common/http";
import {RegisterComponent} from "./security/register/register.component";
import {ApiInterceptor} from "./shared/http/api_interceptor.service";
import {REGISTER, RegisterService} from "./security/register/register.service";

@NgModule({
  declarations: [
    AppComponent,
    RegisterComponent
  ],
  imports: [
    HttpClientModule,
    ReactiveFormsModule,
    BrowserModule,
    AppRoutingModule
  ],
  providers: [
    {provide: HTTP_INTERCEPTORS, useClass: ApiInterceptor, multi: true},
    {provide: REGISTER, useClass: RegisterService}
  ],
  bootstrap: [AppComponent]
})
export class AppModule {
}
