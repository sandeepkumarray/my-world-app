import { BaseModel } from "./BaseModel";
export class CharacterCompanions extends BaseModel {

		public user_id!: number;
		public character_id!: number;
		public companion_id!: number;
		public created_at!: Date;
		public updated_at!: Date;
}
