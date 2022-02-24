import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {AdventureRoutingModule} from "./adventure-routing.module";
import {HttpClientModule} from "@angular/common/http";
import {DashboardComponent} from "./components/dashboard/dashboard.component";
import {CreatePlayerComponent} from "./components/create-player/create-player.component";
import {ReactiveFormsModule} from "@angular/forms";
import {PLAYER_MANAGER_PROVIDER} from "./managers/player-manager.service";

@NgModule({
  declarations: [
    DashboardComponent,
    CreatePlayerComponent
  ],
  imports: [
    HttpClientModule,
    ReactiveFormsModule,
    BrowserModule,
    AdventureRoutingModule
  ],
  providers: [PLAYER_MANAGER_PROVIDER],
  bootstrap: [DashboardComponent]
})
export class AdventureModule {
}
