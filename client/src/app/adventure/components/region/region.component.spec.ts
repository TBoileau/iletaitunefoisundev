import {createRoutingFactory, SpectatorRouting} from "@ngneat/spectator";
import {RegionComponent} from "./region.component";
import {Observable, of} from "rxjs";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {Quest} from "../../entities/quest";
import {Region} from "../../entities/region";
import {Map} from "../../entities/map";
import {REGION_MANAGER_TOKEN, RegionManager} from "../../managers/region-manager.service";

describe('Region component', () => {
  let spectator: SpectatorRouting<RegionComponent>;

  const createComponent = createRoutingFactory({
    component: RegionComponent,
    componentMocks: [RegionManager],
    params: {region: "1"},
    componentProviders: [
      {
        provide: REGION_MANAGER_TOKEN,
        useValue: {
          getMapByRegion(region: number): Observable<Map> {
            const map: Map = {
              region: {
                id: 1,
                name: "Region",
                continent: {
                  id: 1,
                  name: 'Continent',
                  world: {
                    id: 1,
                    name: 'Monde',
                  }
                }
              },
              firstQuest: 1,
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
