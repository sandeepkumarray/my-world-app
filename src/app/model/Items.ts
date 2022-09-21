import { BaseModel } from "./BaseModel";
export class Items extends BaseModel {

		public id!: number;
		public Name!: string;
		public Item_Type!: string;
		public Universe!: number;
		public Description!: string;
		public Tags!: string;
		public Weight!: number;
		public Materials!: string;
		public Past_Owners!: string;
		public Year_it_was_made!: number;
		public Makers!: string;
		public Current_Owners!: string;
		public Original_Owners!: string;
		public Magical_effects!: string;
		public Magic!: string;
		public Technical_effects!: string;
		public Technology!: string;
		public Private_Notes!: string;
		public Notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
