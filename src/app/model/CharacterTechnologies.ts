import { BaseModel } from "./BaseModel";
export class CharacterTechnologies extends BaseModel {

		public user_id!: number;
		public character_id!: number;
		public technology_id!: number;
		public created_at!: Date;
		public updated_at!: Date;
}
