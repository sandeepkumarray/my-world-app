import { BaseModel } from "./BaseModel";
export class CharacterFloras extends BaseModel {

		public user_id!: number;
		public character_id!: number;
		public flora_id!: number;
		public created_at!: Date;
		public updated_at!: Date;
}
