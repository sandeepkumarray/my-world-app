import { BaseModel } from "./BaseModel";
export class CharacterMagics extends BaseModel {

		public character_id!: number;
		public magic_id!: number;
		public user_id!: number;
		public created_at!: Date;
		public updated_at!: Date;
}
