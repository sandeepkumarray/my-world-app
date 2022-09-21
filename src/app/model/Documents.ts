import { BaseModel } from "./BaseModel";
export class Documents extends BaseModel {

		public id? : number;
		public user_id? : string;
		public body! : string;
		public created_at! : Date;
		public updated_at! : Date;
		public title? : string;
		public privacy? : string;
		public synopsis? : string;
		public folder_id? : number;
		public cached_word_count? : number= 0;
		public deleted_at? : string;
		public universe_id? : number = 0;
		public favorite? : string;
		public notes_text? : string;
		public createdSince?:string;
		public timeSince?:string;
		public readingTime?:string;
}
