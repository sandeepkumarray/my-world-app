import { BaseModel } from "./BaseModel";
export class ContentTypes extends BaseModel {

		public id!: number;
		public name!: string;
		public card_image!: string;
		public icon!: string;
		public fa_icon!: string;
		public primary_color!: string;
		public sec_color!: string;
		public created_date!: Date;
		public created_by!: number;
		public count!: number;
		public name_singular!: string;
}
