import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { RegisterComponent } from './register/register.component';
import {RouterModule} from "@angular/router";
import {ReactiveFormsModule} from "@angular/forms";
import {REGISTER} from "./register/register";
import {RegisterService} from "./register/register.service";
import {HTTP_INTERCEPTORS, HttpClientModule} from "@angular/common/http";
import {ApiInterceptor} from "./http/api_interceptor.service";

@NgModule({
  declarations: [
    AppComponent,
    RegisterComponent
  ],
  imports: [
    RouterModule.forRoot([
      { path: 'register', component: RegisterComponent }
    ]),
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
