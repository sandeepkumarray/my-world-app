import { BaseModel } from "./BaseModel";

export class ContentObject extends BaseModel {

		public object_type? : string;
		public object_size? : number;
		public created_at? : string;
		public file? : string;
		public file_url? : string;
		public bucket_folder? : string;
}
