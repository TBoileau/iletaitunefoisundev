import {createHttpFactory, HttpMethod, SpectatorHttp} from '@ngneat/spectator';
import {PlayerManager} from "./player-manager.service";
import {SESSION_PROVIDER} from "../../core/security/session.service";
import {STORAGE_MANAGER_PROVIDER} from "../../core/storage/storage-manager.service";
import {QuestManager} from "./quest-manager.service";
import {Quest} from "../entities/quest";

describe('QuestManager', () => {
  let spectator: SpectatorHttp<QuestManager>;
  const createHttp = createHttpFactory(QuestManager);
  const quest: Quest = {
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
  };

  beforeEach(() => spectator = createHttp());

  it('should get checkpoint', () => {
    spectator.service.getCheckpoint(quest).subscribe();
    spectator.expectOne('/api/adventure/quests/1/checkpoint', HttpMethod.GET);
  });

  it('should start quest', () => {
    spectator.service.start(quest).subscribe();
    spectator.expectOne('/api/adventure/quests/1/start', HttpMethod.POST);
  });

  it('should finish quest', () => {
    spectator.service.finish(quest).subscribe();
    spectator.expectOne('/api/adventure/quests/1/finish', HttpMethod.POST);
  });
});
