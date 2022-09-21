import { BaseModel } from "./BaseModel";
export class Religions extends BaseModel {

		public id!: number;
		public Tags!: string;
		public Name!: string;
		public Description!: string;
		public Other_Names!: string;
		public Universe!: number;
		public Notable_figures!: string;
		public Origin_story!: string;
		public Artifacts!: string;
		public Places_of_worship!: string;
		public Vision_of_paradise!: string;
		public Obligations!: string;
		public Worship_services!: string;
		public Prophecies!: string;
		public Teachings!: string;
		public Deities!: string;
		public Initiation_process!: string;
		public Rituals!: string;
		public Holidays!: string;
		public Traditions!: string;
		public Practicing_locations!: string;
		public Practicing_races!: string;
		public Private_notes!: string;
		public Notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
