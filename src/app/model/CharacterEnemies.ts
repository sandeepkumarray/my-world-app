import { BaseModel } from "./BaseModel";
export class CharacterEnemies extends BaseModel {

		public character_id!: number;
		public enemy_id!: number;
		public user_id!: number;
		public created_at!: Date;
		public updated_at!: Date;
}
