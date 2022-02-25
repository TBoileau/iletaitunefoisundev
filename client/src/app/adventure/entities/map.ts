import {Quest} from "./quest";
import {Relation} from "./relation";

export interface Map {
  quests: {[key: number]: Quest};
  relations: {[key: number]: Array<Relation>};
}
