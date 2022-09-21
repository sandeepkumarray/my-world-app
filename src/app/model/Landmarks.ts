import { BaseModel } from "./BaseModel";
export class Landmarks extends BaseModel {

		public id!: number;
		public Name!: string;
		public Tags!: string;
		public Description!: string;
		public Other_Names!: string;
		public Type_of_landmark!: string;
		public Universe!: number;
		public Country!: string;
		public Nearby_towns!: string;
		public Size!: number;
		public Colors!: string;
		public Materials!: string;
		public Creatures!: string;
		public Flora!: string;
		public Creation_story!: string;
		public Established_year!: number;
		public Notes!: string;
		public Private_Notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
