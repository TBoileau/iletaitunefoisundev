import {createRoutingFactory, SpectatorRouting, SpyObject} from "@ngneat/spectator";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {RouterTestingModule} from "@angular/router/testing";
import {ReactiveFormsModule} from "@angular/forms";
import {of, throwError} from "rxjs";
import {Router} from "@angular/router";
import {HttpErrorResponse} from "@angular/common/http";
import {CreatePlayerComponent} from "./create-player.component";
import {CREATE_PLAYER_TOKEN, CreatePlayer, CreatePlayerInterface, NewPlayer} from "./create-player.service";
import {DASHBOARD_ROUTE} from "../dashboard/dashboard.component";
import {Player} from "../../entities/player";

describe('Create player component', () => {
  let spectator: SpectatorRouting<CreatePlayerComponent>;
  let createPlayer: SpyObject<CreatePlayerInterface>

  const createComponent = createRoutingFactory({
    component: CreatePlayerComponent,
    componentMocks: [CreatePlayer],
    componentProviders: [
      {provide: CREATE_PLAYER_TOKEN, useExisting: CreatePlayer},
    ],
    imports: [HttpClientTestingModule, RouterTestingModule.withRoutes([DASHBOARD_ROUTE]), ReactiveFormsModule]
  });

  beforeEach(() => {
    spectator = createComponent();
    createPlayer = spectator.inject(CreatePlayer, true);
  });

  it('should submit form and redirect to dashboard', () => {
    const player: Player = {
      id: 1,
      name: 'Name',
      journey: {
        id: 1,
        checkpoints: []
      }
    };
    createPlayer.create.and.returnValue(of(player));
    const router = spectator.inject(Router, true);
    spectator.typeInElement('Player', '.input-name');
    spectator.click('.form-submit');
    const newPlayer: NewPlayer = {
      name: 'Player'
    };
    expect(createPlayer.create).toHaveBeenCalledWith(newPlayer);
    expect(router.navigate).toHaveBeenCalledWith(['/dashboard']);
  });

  it('should submit form and raise errors', () => {
    spyOn(spectator.component.createPlayerForm, 'mergeErrors');
    createPlayer.create.and.returnValue(throwError(() => new HttpErrorResponse({
      error: {
        message: 'Error'
      }
    })));
    spectator.typeInElement('Player', '.input-name');
    spectator.click('.form-submit');
    expect(spectator.component.createPlayerForm.mergeErrors).toHaveBeenCalled();
  });
});
