import {createRoutingFactory, SpectatorRouting} from "@ngneat/spectator";
import {Observable, of} from "rxjs";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {Quest} from "../../entities/quest";
import {Region} from "../../entities/region";
import {Map} from "../../entities/map";
import {REGION_MANAGER_TOKEN, RegionManager} from "../../managers/region-manager.service";
import {QuestComponent} from "./quest.component";
import {YoutubePipe} from "../../pipe/youtube.pipe";
import {QUEST_MANAGER_TOKEN, QuestManager} from "../../managers/quest-manager.service";
import {Checkpoint} from "../../entities/checkpoint";

describe('Quest component', () => {
  let spectator: SpectatorRouting<QuestComponent>;

  const createComponent = createRoutingFactory({
    component: QuestComponent,
    componentMocks: [RegionManager, QuestManager],
    params: {region: "1", quest: "1"},
    declarations: [YoutubePipe],
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
                    youtubeUrl: "https://www.youtube.com/watch?v=-S94RNjjb4I",
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
      },
      {
        provide: QUEST_MANAGER_TOKEN,
        useValue: {
          getCheckpoint(quest: Quest): Observable<Checkpoint|null> {
            return of(null);
          },
          start(quest: Quest): Observable<Checkpoint> {
            return of({
              id: 1,
              startedAt: new Date(),
              finishedAt: null
            });
          },
          finish(quest: Quest): Observable<Checkpoint> {
            return of({
              id: 1,
              startedAt: new Date(),
              finishedAt: new Date()
            });
          },
        }
      }
    ],
    imports: [HttpClientTestingModule]
  });

  it("should start and finish quest", async () => {
    spectator = createComponent();
    await spectator.fixture.whenStable();
    expect(spectator.query('iframe')).toHaveLength(0);
    expect(spectator.query('.btn-start')).toHaveLength(1);
    spectator.click('.btn-start');
    await spectator.fixture.whenStable();
    expect(spectator.query('.btn-start')).toHaveLength(0);
    expect(spectator.query('iframe')).toHaveLength(1);
    expect(spectator.query('.btn-finish')).toHaveLength(1);
    spectator.click('.btn-finish');
    await spectator.fixture.whenStable();
    expect(spectator.query('.btn-start')).toHaveLength(0);
    expect(spectator.query('.btn-finish')).toHaveLength(0);
  });
});
