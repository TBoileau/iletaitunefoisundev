import {Component, OnInit} from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Component({
  selector: 'app-login',
  template: '<h1>{{ player.name }}</h1>',
})
export class TestComponent implements OnInit {
  public player: { name: string } = {
    name: ''
  };

  constructor(private http: HttpClient) {
  }


  ngOnInit(): void {
    this.http.get<{ name: string }>('/api/adventure/players/me').subscribe({
      next: player => this.player = player
    })
  }
}
