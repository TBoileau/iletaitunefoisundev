import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {DASHBOARD_ROUTE} from "./components/dashboard/dashboard.component";
import {CREATE_PLAYER_ROUTE} from "./components/create-player/create-player.component";
import {WORLDS_ROUTE} from "./components/worlds/worlds.component";
import {WORLD_ROUTE} from "./components/world/world.component";
import {CONTINENT_ROUTE} from "./components/continent/continent.component";

const routes: Routes = [
  DASHBOARD_ROUTE,
  CREATE_PLAYER_ROUTE,
  WORLDS_ROUTE,
  WORLD_ROUTE,
  CONTINENT_ROUTE
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AdventureRoutingModule {
}
