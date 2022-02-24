import {Region} from "./region";

export interface Continent {
  id: number;
  name: string;
  regions: Array<Region>;
}
