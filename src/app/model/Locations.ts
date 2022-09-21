import { BaseModel } from "./BaseModel";
export class Locations extends BaseModel {

		public id!: number;
		public Description!: string;
		public Universe!: number;
		public Tags!: string;
		public Name!: string;
		public Type!: string;
		public Leaders!: string;
		public Language!: string;
		public Population!: number;
		public Currency!: string;
		public Motto!: string;
		public Sports!: string;
		public Laws!: string;
		public Spoken_Languages!: string;
		public Largest_cities!: string;
		public Notable_cities!: string;
		public Capital_cities!: string;
		public Landmarks!: string;
		public Area!: number;
		public Crops!: string;
		public Located_at!: string;
		public Climate!: string;
		public Notable_Wars!: string;
		public Founding_Story!: string;
		public Established_Year!: number;
		public Notes!: string;
		public Private_Notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
