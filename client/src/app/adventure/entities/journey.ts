import {Checkpoint} from "./checkpoint";

export interface Journey {
  id: number;
  checkpoints: Array<Checkpoint>;
}
