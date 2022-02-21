import {InjectionToken, Provider} from '@angular/core';
import {Observable} from "rxjs";
import {UserConsumer} from "../consumers/user-consumer.service";

export interface Register {
  register(registration: Registration): Observable<User>;
}

export interface Registration {
  email: string;
  plainPassword: string;
}

export interface User {
  id: number;
  email: string;
  forgottenPasswordToken: string | null;
}

export const REGISTER_TOKEN = new InjectionToken<Register>('security.contract.register');

export const REGISTER_PROVIDER: Provider = {
  provide: REGISTER_TOKEN,
  useClass: UserConsumer,
  multi: true
};
