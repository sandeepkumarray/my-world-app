import { BaseModel } from "./BaseModel";
export class Languages extends BaseModel {

		public id!: number;
		public Universe!: number;
		public Tags!: string;
		public Other_Names!: string;
		public Name!: string;
		public Typology!: string;
		public Dialectical_information!: string;
		public Register!: string;
		public History!: string;
		public Evolution!: string;
		public Gestures!: string;
		public Phonology!: string;
		public Grammar!: string;
		public Please!: string;
		public Trade!: string;
		public Family!: string;
		public Body_parts!: string;
		public No_words!: string;
		public Yes_words!: string;
		public Sorry!: string;
		public You_are_welcome!: string;
		public Thank_you!: string;
		public Goodbyes!: string;
		public Greetings!: string;
		public Pronouns!: string;
		public Numbers!: string;
		public Quantifiers!: string;
		public Determiners!: string;
		public Notes!: string;
		public Private_notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
