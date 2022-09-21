import { BaseModel } from "./BaseModel";
export class CharacterBirthtowns extends BaseModel {

		public user_id!: number;
		public character_id!: number;
		public birthtown_id!: number;
		public created_at!: Date;
		public updated_at!: Date;
}
