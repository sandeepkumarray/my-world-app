import { BaseModel } from "./BaseModel";
export class Technologies extends BaseModel {

		public id!: number;
		public Tags!: string;
		public Name!: string;
		public Description!: string;
		public Other_Names!: string;
		public Universe!: number;
		public Sales_Process!: string;
		public Materials!: string;
		public Manufacturing_Process!: string;
		public Cost!: number;
		public Planets!: string;
		public Rarity!: string;
		public Creatures!: string;
		public Groups!: string;
		public Countries!: string;
		public Towns!: string;
		public Characters!: string;
		public Magic_effects!: string;
		public Resources_Used!: string;
		public How_It_Works!: string;
		public Purpose!: string;
		public Weight!: number;
		public Physical_Description!: string;
		public Size!: number;
		public Colors!: string;
		public Related_technologies!: string;
		public Parent_technologies!: string;
		public Child_technologies!: string;
		public Notes!: string;
		public Private_Notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
