import { BaseModel } from "./BaseModel";
export class Groups extends BaseModel {

		public id!: number;
		public Tags!: string;
		public Universe!: number;
		public Other_Names!: string;
		public Description!: string;
		public Name!: string;
		public Subgroups!: string;
		public Supergroups!: string;
		public Sistergroups!: string;
		public Organization_structure!: string;
		public Leaders!: string;
		public Creatures!: string;
		public Members!: string;
		public Offices!: string;
		public Locations!: string;
		public Headquarters!: string;
		public Motivations!: string;
		public Traditions!: string;
		public Risks!: string;
		public Obstacles!: string;
		public Goals!: string;
		public Clients!: string;
		public Allies!: string;
		public Enemies!: string;
		public Rivals!: string;
		public Suppliers!: string;
		public Inventory!: string;
		public Equipment!: string;
		public Key_items!: string;
		public Notes!: string;
		public Private_notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
