import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {RegisterComponent} from "./security/register/register.component";
import {LoginComponent} from "./security/login/login.component";
import {TestComponent} from "./security/test/test.component";
import {GuardService} from "./shared/security/guard.service";

const routes: Routes = [
  {path: 'register', component: RegisterComponent},
  {path: 'login', component: LoginComponent},
  {path: 'test', component: TestComponent, canActivate: [GuardService]},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {
}
