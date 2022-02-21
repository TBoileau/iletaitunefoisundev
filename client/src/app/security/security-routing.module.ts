import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {REGISTER_ROUTE} from "./components/register/register.component";
import {LOGIN_ROUTE} from "./components/login/login.component";

const routes: Routes = [
  REGISTER_ROUTE,
  LOGIN_ROUTE,
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class SecurityRoutingModule {
}
