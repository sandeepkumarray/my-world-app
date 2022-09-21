import { BaseModel } from "./BaseModel";
export class Traditions extends BaseModel {

		public id!: number;
		public Universe!: number;
		public Alternate_names!: string;
		public Tags!: string;
		public Name!: string;
		public Description!: string;
		public Type_of_tradition!: string;
		public Countries!: string;
		public Dates!: string;
		public Groups!: string;
		public Towns!: string;
		public Gifts!: string;
		public Food!: string;
		public Symbolism!: string;
		public Games!: string;
		public Activities!: string;
		public Etymology!: string;
		public Origin!: string;
		public Significance!: string;
		public Religions!: string;
		public Notable_events!: string;
		public Notes!: string;
		public Private_Notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
