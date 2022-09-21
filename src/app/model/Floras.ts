import { BaseModel } from "./BaseModel";
export class Floras extends BaseModel {

		public id!: number;
		public Tags!: string;
		public Universe!: number;
		public Name!: string;
		public Other_Names!: string;
		public Description!: string;
		public Order!: string;
		public Related_flora!: string;
		public Genus!: string;
		public Family!: string;
		public Size!: string;
		public Smell!: string;
		public Taste!: string;
		public Colorings!: string;
		public Fruits!: string;
		public Magical_effects!: string;
		public Material_uses!: string;
		public Medicinal_purposes!: string;
		public Berries!: string;
		public Nuts!: string;
		public Seeds!: string;
		public Seasonality!: string;
		public Locations!: string;
		public Reproduction!: string;
		public Eaten_by!: string;
		public Notes!: string;
		public Private_Notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
