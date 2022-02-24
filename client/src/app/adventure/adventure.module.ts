import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {AdventureRoutingModule} from "./adventure-routing.module";
import {HttpClientModule} from "@angular/common/http";
import {DashboardComponent} from "./components/dashboard/dashboard.component";
import {CreatePlayerComponent} from "./components/create-player/create-player.component";
import {ReactiveFormsModule} from "@angular/forms";
import {PLAYER_MANAGER_PROVIDER} from "./managers/player-manager.service";
import {WorldsComponent} from "./components/worlds/worlds.component";
import {WorldComponent} from "./components/world/world.component";
import {WORLD_MANAGER_PROVIDER} from "./managers/world-manager.service";
import {ContinentComponent} from "./components/continent/continent.component";

@NgModule({
  declarations: [
    DashboardComponent,
    CreatePlayerComponent,
    WorldsComponent,
    WorldComponent,
    ContinentComponent,
  ],
  imports: [
    HttpClientModule,
    ReactiveFormsModule,
    BrowserModule,
    AdventureRoutingModule
  ],
  providers: [PLAYER_MANAGER_PROVIDER, WORLD_MANAGER_PROVIDER],
  bootstrap: [DashboardComponent]
})
export class AdventureModule {
}