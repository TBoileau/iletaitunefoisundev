import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {ReactiveFormsModule} from "@angular/forms";
import {HttpClientModule} from "@angular/common/http";
import {SecurityRoutingModule} from "./security-routing.module";
import {STORAGE_MANAGER_PROVIDER} from "../shared/storage/storage-manager.service";
import {RegisterComponent} from "./components/register/register.component";
import {LoginComponent} from "./components/login/login.component";
import {SESSION_PROVIDER} from "./contracts/session";

@NgModule({
  declarations: [
    RegisterComponent,
    LoginComponent,
  ],
  imports: [
    HttpClientModule,
    ReactiveFormsModule,
    BrowserModule,
    SecurityRoutingModule
  ],
  providers: [
    SESSION_PROVIDER,
    STORAGE_MANAGER_PROVIDER
  ],
  bootstrap: []
})
export class SecurityModule {
}
