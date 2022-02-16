import {Registration} from "./registration";
import {InjectionToken} from "@angular/core";
import {Observable} from "rxjs";
import {User} from "../models/user";

export interface Register {
  execute(registration: Registration): Observable<User>;
}
export const REGISTER = new InjectionToken<Register>('Register');
