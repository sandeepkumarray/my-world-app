import { BaseModel } from "./BaseModel";
export class Magics extends BaseModel {

		public id!: number;
		public Tags!: string;
		public Name!: string;
		public Description!: string;
		public Type_of_magic!: string;
		public Universe!: number;
		public Effects!: string;
		public Visuals!: string;
		public Aftereffects!: string;
		public Conditions!: string;
		public Scale!: number;
		public Negative_effects!: string;
		public Neutral_effects!: string;
		public Positive_effects!: string;
		public Deities!: string;
		public Element!: string;
		public Materials_required!: string;
		public Skills_required!: string;
		public Education!: string;
		public Resource_costs!: string;
		public Limitations!: string;
		public Private_notes!: string;
		public Notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
