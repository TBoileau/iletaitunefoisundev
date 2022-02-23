import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {CoreRoutingModule} from "./core-routing.module";
import {AppComponent} from "./app/app.component";
import {SecurityModule} from "../security/security.module";
import {API_INTERCEPTOR_PROVIDER} from "./http/interfaceptors/api.interceptor";
import {AUTH_INTERCEPTOR_PROVIDER} from "../security/http/interceptors/auth.interceptor";
import {AdventureModule} from "../adventure/adventure.module";

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
    AUTH_INTERCEPTOR_PROVIDER
  ],
  bootstrap: [AppComponent]
})
export class CoreModule {
}
