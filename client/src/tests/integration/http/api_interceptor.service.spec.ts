import {inject, TestBed} from '@angular/core/testing';
import {HTTP_INTERCEPTORS, HttpClient} from "@angular/common/http";
import {HttpClientTestingModule, HttpTestingController} from "@angular/common/http/testing";
import {ApiInterceptor} from "../../../app/shared/http/api_interceptor.service";

describe('ApiInterceptor', () => {
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [
        HttpClientTestingModule
      ],
      providers: [
        {provide: HTTP_INTERCEPTORS, useClass: ApiInterceptor, multi: true}
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
