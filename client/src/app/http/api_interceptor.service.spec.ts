import {inject, TestBed} from '@angular/core/testing';
import {HTTP_INTERCEPTORS, HttpClient, HttpEvent, HttpHandler, HttpRequest, HttpResponse} from "@angular/common/http";
import {ApiInterceptor} from "./api_interceptor.service";
import {Observable, of} from "rxjs";
import {HttpClientTestingModule, HttpTestingController} from "@angular/common/http/testing";

class FakeHandler extends HttpHandler {
  handle(req: HttpRequest<any>): Observable<HttpEvent<any>> {
    return of(new HttpResponse())
  }
}
describe('ApiInterceptor', () => {
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [
        HttpClientTestingModule
      ],
      providers: [
        { provide: HTTP_INTERCEPTORS, useClass: ApiInterceptor, multi: true }
      ]
    });
    httpMock = TestBed.inject(HttpTestingController);
  });

  it('should add api url to http request', inject([HttpClient], (http: HttpClient) => {
    http.get('/api').subscribe();
    const request = httpMock.expectOne('http://localhost:8000/api');
    expect(request.request.url).toBe('http://localhost:8000/api');
  }));
});
