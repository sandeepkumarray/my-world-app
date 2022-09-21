import { BaseModel } from "./BaseModel";
export class Universes extends BaseModel {

		public id!: number;
		public name!: string;
		public description!: string;
		public history!: string;
		public notes!: string;
		public private_notes!: string;
		public privacy!: boolean;
		public user_id!: number;
		public created_at!: Date;
		public updated_at!: Date;
		public laws_of_physics!: string;
		public magic_system!: string;
		public technology!: string;
		public genre!: string;
		public deleted_at!: Date;
		public page_type!: string;
		public archived_at!: Date;
		public favorite!: boolean;
}
