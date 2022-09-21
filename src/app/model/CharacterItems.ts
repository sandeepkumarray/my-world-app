import { BaseModel } from "./BaseModel";
export class CharacterItems extends BaseModel {

		public user_id!: number;
		public character_id!: number;
		public item_id!: number;
		public created_at!: Date;
		public updated_at!: Date;
}
