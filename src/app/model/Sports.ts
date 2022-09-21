import { BaseModel } from "./BaseModel";
export class Sports extends BaseModel {

		public id!: number;
		public Tags!: string;
		public Universe!: number;
		public Nicknames!: string;
		public Description!: string;
		public Name!: string;
		public How_to_win!: string;
		public Penalties!: string;
		public Scoring!: string;
		public Number_of_players!: number;
		public Equipment!: string;
		public Play_area!: string;
		public Most_important_muscles!: string;
		public Common_injuries!: string;
		public Strategies!: string;
		public Positions!: number;
		public Game_time!: number;
		public Rules!: string;
		public Traditions!: string;
		public Teams!: string;
		public Countries!: string;
		public Players!: string;
		public Popularity!: string;
		public Merchandise!: string;
		public Uniforms!: string;
		public Famous_games!: string;
		public Evolution!: string;
		public Creators!: string;
		public Origin_story!: string;
		public Private_Notes!: string;
		public Notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
