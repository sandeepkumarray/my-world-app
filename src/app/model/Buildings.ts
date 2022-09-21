import { BaseModel } from "./BaseModel";
export class Buildings extends BaseModel {

		public id!: number;
		public Name!: string;
		public Universe!: number;
		public Description!: string;
		public Type_of_building!: string;
		public Alternate_names!: string;
		public Tags!: string;
		public Capacity!: number;
		public Price!: number;
		public Owner!: string;
		public Tenants!: string;
		public Affiliation!: string;
		public Facade!: string;
		public Floor_count!: number;
		public Dimensions!: number;
		public Architectural_style!: string;
		public Permits!: string;
		public Purpose!: string;
		public Address!: string;
		public Architect!: string;
		public Developer!: string;
		public Notable_events!: string;
		public Constructed_year!: number;
		public Construction_cost!: number;
		public Notes!: string;
		public Private_Notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
