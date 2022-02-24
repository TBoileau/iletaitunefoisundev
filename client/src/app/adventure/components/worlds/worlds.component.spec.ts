import {createRoutingFactory, SpectatorRouting} from "@ngneat/spectator";
import {WorldsComponent} from "./worlds.component";
import {Location} from "@angular/common";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {WORLD_MANAGER_TOKEN, WorldManager} from "../../managers/world-manager.service";
import {Observable, of} from "rxjs";
import {World} from "../../entities/world";
import {WorldComponent} from "../world/world.component";

describe('Worlds component', () => {
  let spectator: SpectatorRouting<WorldsComponent>;

  const createComponent = createRoutingFactory({
    component: WorldsComponent,
    componentMocks: [WorldManager],
    declarations: [WorldComponent],
    stubsEnabled: false,
    routes: [
      {
        path: 'worlds/:id',
        component: WorldComponent
      },
    ],
    componentProviders: [
      {
        provide: WORLD_MANAGER_TOKEN,
        useValue: {
          getWorlds(): Observable<Array<World>> {
            const worlds: Array<World> = [
              {
                id: 1,
                name: 'Monde',
                continents: []
              }
            ];
            return of(worlds);
          }
        }
      }
    ],
    imports: [HttpClientTestingModule]
  });

  it("should show worlds and click on a continent", async () => {
    spectator = createComponent();
    await spectator.fixture.whenStable();
    expect(spectator.query('ul > li')).toHaveLength(1);
    spectator.click('ul > li:first-child > a');
    await spectator.fixture.whenStable();
    expect(spectator.inject(Location).path()).toBe('/worlds/1');
  });
});
