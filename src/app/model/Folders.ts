import { NumberFormatStyle } from "@angular/common";

export class Folders {

		public procedureName? : string;
		public id? : number;
		public title? : string;
		public context? : string;
		public parent_folder_id? : NumberFormatStyle;
		public user_id? : string;
		public created_at! : Date;
		public updated_at? : string;
		public timeSince?:string;
}
