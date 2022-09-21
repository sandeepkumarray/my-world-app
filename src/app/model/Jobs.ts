import { BaseModel } from "./BaseModel";
export class Jobs extends BaseModel {

		public id!: number;
		public Name!: string;
		public Universe!: number;
		public Description!: string;
		public Type_of_job!: string;
		public Alternate_names!: string;
		public Tags!: string;
		public Experience!: string;
		public Education!: string;
		public Work_hours!: number;
		public Vehicles!: string;
		public Training!: string;
		public Long_term_risks!: string;
		public Occupational_hazards!: string;
		public Pay_rate!: number;
		public Time_off!: string;
		public Similar_jobs!: string;
		public Promotions!: string;
		public Specializations!: string;
		public Field!: string;
		public Ranks!: string;
		public Traditions!: string;
		public Job_origin!: string;
		public Initial_goal!: string;
		public Notable_figures!: string;
		public Notes!: string;
		public Private_Notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
