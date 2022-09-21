import { BaseModel } from "./BaseModel";
export class Races extends BaseModel {

		public id!: number;
		public Other_Names!: string;
		public Universe!: number;
		public Tags!: string;
		public Description!: string;
		public Name!: string;
		public General_weight!: number;
		public Notable_features!: string;
		public Physical_variance!: string;
		public Typical_clothing!: string;
		public Body_shape!: number;
		public Skin_colors!: string;
		public General_height!: number;
		public Weaknesses!: string;
		public Conditions!: string;
		public Strengths!: string;
		public Favorite_foods!: string;
		public Famous_figures!: string;
		public Traditions!: string;
		public Beliefs!: string;
		public Governments!: string;
		public Technologies!: string;
		public Occupations!: string;
		public Economics!: string;
		public Notable_events!: string;
		public Notes!: string;
		public Private_notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
