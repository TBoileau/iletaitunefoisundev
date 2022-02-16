import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import {RouterModule} from "@angular/router";
import {ReactiveFormsModule} from "@angular/forms";
import {HTTP_INTERCEPTORS, HttpClientModule} from "@angular/common/http";
import {RegisterComponent} from "./security/register/register.component";
import {ApiInterceptor} from "./shared/http/api_interceptor.service";
import {RegisterService} from "./security/register/register.service";
import {REGISTER} from "./security/register/register";

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
    { provide: HTTP_INTERCEPTORS, useClass: ApiInterceptor, multi: true },
    { provide: REGISTER, useClass: RegisterService }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
