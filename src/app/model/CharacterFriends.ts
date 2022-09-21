import { BaseModel } from "./BaseModel";
export class CharacterFriends extends BaseModel {

		public user_id!: number;
		public character_id!: number;
		public friend_id!: number;
		public created_at!: Date;
		public updated_at!: Date;
}
