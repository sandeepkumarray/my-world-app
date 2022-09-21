import { BaseModel } from "./BaseModel";
export class Scenes extends BaseModel {

		public id!: number;
		public Tags!: string;
		public Name!: string;
		public Summary!: string;
		public Universe!: number;
		public Items_in_scene!: string;
		public Locations_in_scene!: string;
		public Characters_in_scene!: string;
		public Description!: string;
		public Results!: string;
		public What_caused_this!: string;
		public Notes!: string;
		public Private_notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
