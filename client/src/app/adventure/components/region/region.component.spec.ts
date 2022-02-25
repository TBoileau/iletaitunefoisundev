import {createRoutingFactory, SpectatorRouting} from "@ngneat/spectator";
import {RegionComponent} from "./region.component";
import {WORLD_MANAGER_TOKEN, WorldManager} from "../../managers/world-manager.service";
import {Observable, of} from "rxjs";
import {World} from "../../entities/world";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {QUEST_MANAGER_TOKEN, QuestManager} from "../../managers/quest-manager.service";
import {Quest} from "../../entities/quest";
import {Region} from "../../entities/region";

describe('Continent component', () => {
  let spectator: SpectatorRouting<RegionComponent>;

  const createComponent = createRoutingFactory({
    component: RegionComponent,
    componentMocks: [WorldManager, QuestManager],
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
        provide: QUEST_MANAGER_TOKEN,
        useValue: {
          getQuestsByRegion(region: Region): Observable<Array<Quest>> {
            const quests: Array<Quest> = [{
              id: 1,
              name: "Quest",
              quiz: "/api/content/quizzes/1",
              course: {
                youtubeUrl: "",
                description: "",
                content: "",
                title: "",
              },
              difficultyName: "Hard",
              typeName: "Main",
            }];
            return of(quests);
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
