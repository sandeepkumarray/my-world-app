import { BaseModel } from "./BaseModel";
export class CharacterLoveInterests extends BaseModel {

		public user_id!: number;
		public character_id!: number;
		public love_interest_id!: number;
		public created_at!: Date;
		public updated_at!: Date;
}
