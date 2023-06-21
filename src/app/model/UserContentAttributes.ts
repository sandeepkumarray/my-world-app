import { BaseModel } from "./BaseModel";
export class UserContentAttributes extends BaseModel {

		public content_id!: number;
		public name!: string;
		public user_id!: number;
		public universe_id!: number;
		public universe_name!: string;
}
