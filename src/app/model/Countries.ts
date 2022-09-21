import { BaseModel } from "./BaseModel";
export class Countries extends BaseModel {

		public id!: number;
		public Tags!: string;
		public Name!: string;
		public Description!: string;
		public Other_Names!: string;
		public Universe!: number;
		public Landmarks!: string;
		public Locations!: string;
		public Towns!: string;
		public Bordering_countries!: string;
		public Education!: string;
		public Governments!: string;
		public Religions!: string;
		public Languages!: string;
		public Sports!: string;
		public Architecture!: string;
		public Music!: string;
		public Pop_culture!: string;
		public Laws!: string;
		public Currency!: string;
		public Social_hierarchy!: string;
		public Population!: number;
		public Area!: number;
		public Crops!: string;
		public Climate!: string;
		public Creatures!: string;
		public Flora!: string;
		public Established_year!: number;
		public Notable_wars!: string;
		public Founding_story!: string;
		public Private_Notes!: string;
		public Notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
