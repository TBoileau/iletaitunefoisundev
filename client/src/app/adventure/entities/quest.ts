import {Course} from "./course";

export interface Quest {
  id: number;
  name: string;
  quiz: string;
  course: Course;
  difficultyName: string;
  typeName: string;
}
