import {Injectable, InjectionToken} from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs";

@Injectable({
  providedIn: 'root'
})
export class RegisterService implements Register {
  constructor(private http: HttpClient) {
  }

  execute(registration: RegisterInput): Observable<RegisterOutput> {
    return this.http.post<RegisterOutput>('/api/security/register', registration);
  }
}

export interface RegisterInput {
  email: string;
  plainPassword: string;
}

export interface RegisterOutput {
  id: number;
  email: string;
  forgottenPasswordToken: string | null;
}

export interface Register {
  execute(registration: RegisterInput): Observable<RegisterOutput>;
}

export const REGISTER = new InjectionToken<Register>('Register');

