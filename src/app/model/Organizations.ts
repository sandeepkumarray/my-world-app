import { BaseModel } from "./BaseModel";
export class Organizations extends BaseModel {

		public id!: number;
		public Name!: string;
		public Universe!: number;
		public Description!: string;
		public Type_of_organization!: string;
		public Alternate_names!: string;
		public Tags!: string;
		public Owner!: string;
		public Members!: string;
		public Purpose!: string;
		public Services!: string;
		public Sub_organizations!: string;
		public Super_organizations!: string;
		public Sister_organizations!: string;
		public Organization_structure!: string;
		public Rival_organizations!: string;
		public Address!: string;
		public Offices!: string;
		public Locations!: string;
		public Headquarters!: string;
		public Formation_year!: number;
		public Closure_year!: number;
		public Notes!: string;
		public Private_Notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
