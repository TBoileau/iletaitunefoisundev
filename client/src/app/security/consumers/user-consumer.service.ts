import {Register, Registration, User} from "../contracts/register";
import {Observable} from "rxjs";
import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";

@Injectable({
  providedIn: 'root'
})
export class UserConsumer implements Register {
  constructor(private http: HttpClient) {
  }

  register(registration: Registration): Observable<User> {
    return this.http.post<User>('/api/security/register', registration);
  }
}
