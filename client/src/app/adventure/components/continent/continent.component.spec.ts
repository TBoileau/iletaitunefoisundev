import {createRoutingFactory, SpectatorRouting} from "@ngneat/spectator";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {WORLD_MANAGER_TOKEN, WorldManager} from "../../managers/world-manager.service";
import {Observable, of} from "rxjs";
import {World} from "../../entities/world";
import {ContinentComponent} from "../continent/continent.component";

describe('Continent component', () => {
  let spectator: SpectatorRouting<ContinentComponent>;

  const createComponent = createRoutingFactory({
    component: ContinentComponent,
    componentMocks: [WorldManager],
    params: {world: "1", continent: "1"},
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
                    regions: [
                      {
                        id: 1,
                        name: "Region",
                      }
                    ]
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

  it("should show regions", async () => {
    spectator = createComponent();
    await spectator.fixture.whenStable();
    expect(spectator.query('ul > li')).toHaveLength(1);
  });
});
