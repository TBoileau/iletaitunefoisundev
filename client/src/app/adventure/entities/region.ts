import {Continent} from "./continent";

export interface Region {
  id: number;
  name: string;
  continent?: Continent;
}
