import { BaseModel } from "./BaseModel";
export class Vehicles extends BaseModel {

		public id!: number;
		public Universe!: number;
		public Type_of_vehicle!: string;
		public Alternate_names!: string;
		public Tags!: string;
		public Name!: string;
		public Description!: string;
		public Dimensions!: number;
		public Size!: number;
		public Doors!: number;
		public Materials!: string;
		public Designer!: string;
		public Windows!: number;
		public Colors!: string;
		public Distance!: string;
		public Features!: string;
		public Safety!: string;
		public Fuel!: number;
		public Speed!: number;
		public Variants!: string;
		public Manufacturer!: string;
		public Costs!: number;
		public Weight!: number;
		public Country!: number;
		public Owner!: string;
		public Notes!: string;
		public Private_Notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
