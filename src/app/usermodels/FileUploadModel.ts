
export class FileUploadModel {
    public id!: string;
    public user_id?: string;
	public inProgress?: boolean;
	public progress?: any;
    public name? : any;
    public blob? : any;
    public file_type? : any;
	public size? : number;
	public type? : string;
	public tableName? : string;
	public tableColumns? : string;
}
