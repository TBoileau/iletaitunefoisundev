import {createRoutingFactory, SpectatorRouting} from "@ngneat/spectator";
import {Location} from "@angular/common";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {WORLD_MANAGER_TOKEN, WorldManager} from "../../managers/world-manager.service";
import {Observable, of} from "rxjs";
import {World} from "../../entities/world";
import {ContinentComponent} from "../continent/continent.component";
import {WorldComponent} from "./world.component";

describe('World component', () => {
  let spectator: SpectatorRouting<WorldComponent>;

  const createComponent = createRoutingFactory({
    component: WorldComponent,
    componentMocks: [WorldManager],
    params: {world: "1"},
    declarations: [ContinentComponent],
    stubsEnabled: false,
    routes: [
      {
        path: 'worlds/:world/continents/:continent',
        component: ContinentComponent
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
                continents: [
                  {
                    id: 1,
                    name: 'Continent',
                    regions: []
                  }
                ]
              }
            ];
            return of(worlds);
          }
        }
      }
    ],
    imports: [HttpClientTestingModule]
  });

  it("should show continents and click on a regions", async () => {
    spectator = createComponent();
    await spectator.fixture.whenStable();
    expect(spectator.query('ul > li')).toHaveLength(1);
    spectator.click('ul > li:first-child > a');
    await spectator.fixture.whenStable();
    expect(spectator.inject(Location).path()).toBe('/worlds/1/continents/1');
  });
});
