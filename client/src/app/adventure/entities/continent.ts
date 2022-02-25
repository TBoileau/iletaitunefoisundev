import {Region} from "./region";
import {World} from "./world";

export interface Continent {
  id: number;
  name: string;
  regions?: Array<Region>;
  world?: World;
}
