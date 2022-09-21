import { BaseModel } from "./BaseModel";
export class Towns extends BaseModel {

		public id!: number;
		public Universe!: number;
		public Tags!: string;
		public Name!: string;
		public Description!: string;
		public Other_names!: string;
		public Country!: number;
		public Groups!: string;
		public Citizens!: number;
		public Buildings!: number;
		public Neighborhoods!: number;
		public Busy_areas!: string;
		public Landmarks!: string;
		public Laws!: string;
		public Languages!: string;
		public Flora!: string;
		public Creatures!: string;
		public Politics!: string;
		public Sports!: string;
		public Established_year!: number;
		public Founding_story!: string;
		public Food_sources!: string;
		public Waste!: string;
		public Energy_sources!: string;
		public Recycling!: string;
		public Notes!: string;
		public Private_Notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
