import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {Register} from "./register";
import {Registration} from "./registration";
import {User} from "../models/user";
import {Observable} from "rxjs";

@Injectable({
  providedIn: 'root'
})
export class RegisterService implements Register{
  constructor(private http: HttpClient) {
  }

  execute(registration: Registration): Observable<User> {
    return this.http.post<User>('/api/security/register', registration);
  }
}
