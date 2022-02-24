import {Observable} from "rxjs";
import {HttpClient} from "@angular/common/http";
import {Injectable, InjectionToken, Provider} from "@angular/core";

@Injectable({
  providedIn: 'root'
})
export class Register implements RegisterInterface {
  constructor(private http: HttpClient) {
  }

  register(registration: Registration): Observable<User> {
    return this.http.post<User>('/api/security/register', registration);
  }
}

export interface RegisterInterface {
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

export const REGISTER_TOKEN = new InjectionToken<RegisterInterface>('security.register');

export const REGISTER_PROVIDER: Provider = {
  provide: REGISTER_TOKEN,
  useClass: Register
};
