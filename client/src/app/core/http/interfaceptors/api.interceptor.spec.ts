import {inject, TestBed} from '@angular/core/testing';
import {HttpClient} from "@angular/common/http";
import {HttpClientTestingModule, HttpTestingController} from "@angular/common/http/testing";
import {API_INTERCEPTOR_PROVIDER, ApiInterceptor} from "./api.interceptor";

describe('ApiInterceptor', () => {
  let httpMock: HttpTestingController;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [HttpClientTestingModule],
      providers: [API_INTERCEPTOR_PROVIDER]
    });
    httpMock = TestBed.inject(HttpTestingController);
  });

  it('should add api url to http request', inject([HttpClient], (http: HttpClient) => {
    http.get('/api').subscribe();
    const request = httpMock.expectOne('http://localhost:8000/api');
    expect(request.request.url).toBe('http://localhost:8000/api');
  }));
});
