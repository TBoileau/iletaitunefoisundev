import {Quest} from "./quest";
import {Relation} from "./relation";
import {Region} from "./region";

export interface Map {
  region: Region,
  firstQuest: number,
  quests: { [key: number]: Quest };
  relations: { [key: number]: Array<Relation> };
}
