import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {CoreRoutingModule} from "./core-routing.module";
import {SecurityModule} from "../security/security.module";
import {AdventureModule} from "../adventure/adventure.module";
import {AppComponent} from "./components/app/app.component";
import {API_INTERCEPTOR_PROVIDER} from "./interfaceptors/api.interceptor";
import {AUTH_INTERCEPTOR_PROVIDER} from "./interfaceptors/auth.interceptor";
import {SESSION_PROVIDER} from "./security/session.service";
import {STORAGE_MANAGER_PROVIDER} from "./storage/storage-manager.service";
import {REFRESH_TOKEN_PROVIDER} from "./security/authenticator.service";

@NgModule({
  declarations: [
    AppComponent,
  ],
  imports: [
    AdventureModule,
    SecurityModule,
    BrowserModule,
    CoreRoutingModule
  ],
  providers: [
    API_INTERCEPTOR_PROVIDER,
    AUTH_INTERCEPTOR_PROVIDER,
    SESSION_PROVIDER,
    STORAGE_MANAGER_PROVIDER,
    REFRESH_TOKEN_PROVIDER
  ],
  bootstrap: [AppComponent]
})
export class CoreModule {
}
