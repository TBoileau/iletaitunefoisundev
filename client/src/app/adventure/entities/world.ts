import {Continent} from "./continent";

export interface World {
  id: number;
  name: string;
  continents?: Array<Continent>;
}
