import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {DASHBOARD_ROUTE} from "./components/dashboard/dashboard.component";
import {CREATE_PLAYER_ROUTE} from "./components/create-player/create-player.component";

const routes: Routes = [
  DASHBOARD_ROUTE,
  CREATE_PLAYER_ROUTE
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AdventureRoutingModule {
}
