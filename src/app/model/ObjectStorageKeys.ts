import { BaseModel } from "./BaseModel";

export class ObjectStorageKeys extends BaseModel{

		public id? : string;
		public endpoint? : string;
		public accessKey? : string;
		public secretKey? : string;
		public bucketName? : string;
		public location? : string;
		public Created_at? : string;
}
