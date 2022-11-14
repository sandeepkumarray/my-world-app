import { BaseModel } from "./BaseModel";
export class ContentBlobObject extends BaseModel {
	public created_at? : string;
	public object_blob? : any;
	public object_size? : number;
	public object_type? : string;
	public content_id? : string;
	public inProgress?: boolean;
	public progress?: any;
	public size?:string;
	public user_id!: number;
}
