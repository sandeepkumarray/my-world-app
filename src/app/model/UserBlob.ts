
import { BaseModel } from "./BaseModel";
export class UserBlob extends BaseModel {

		public blob!: any;
		public blob_type!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
		public file_type!: string;
}
