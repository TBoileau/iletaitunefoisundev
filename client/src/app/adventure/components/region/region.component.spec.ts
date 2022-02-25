import {createRoutingFactory, SpectatorRouting} from "@ngneat/spectator";
import {RegionComponent} from "./region.component";
import {WORLD_MANAGER_TOKEN, WorldManager} from "../../managers/world-manager.service";
import {Observable, of} from "rxjs";
import {World} from "../../entities/world";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {Quest} from "../../entities/quest";
import {Region} from "../../entities/region";
import {Map} from "../../entities/map";
import {REGION_MANAGER_TOKEN, RegionManager} from "../../managers/region-manager.service";

describe('Continent component', () => {
  let spectator: SpectatorRouting<RegionComponent>;

  const createComponent = createRoutingFactory({
    component: RegionComponent,
    componentMocks: [WorldManager, RegionManager],
    params: {world: "1", continent: "1", region: "1"},
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
                        name: "Region"
                      }
                    ]
                  }
                ]
              }
            ];
            return of(worlds);
          }
        }
      },
      {
        provide: REGION_MANAGER_TOKEN,
        useValue: {
          getMapByRegion(region: Region): Observable<Map> {
            const map: Map = {
              quests: {
                1: {
                  id: 1,
                  name: "Quest",
                  quiz: "/api/content/quizzes/1",
                  course: {
                    youtubeUrl: "",
                    description: "",
                    content: "",
                    title: "",
                  },
                  difficultyName: "HARD",
                  typeName: "MAIN"
                }
              },
              relations: []
            };
            return of(map);
          }
        }
      }
    ],
    imports: [HttpClientTestingModule]
  });

  it("should show quests", async () => {
    spectator = createComponent();
    await spectator.fixture.whenStable();
    expect(spectator.query('ul > li')).toHaveLength(1);
  });
});
