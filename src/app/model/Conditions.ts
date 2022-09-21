import { BaseModel } from "./BaseModel";
export class Conditions extends BaseModel {

		public id!: number;
		public Tags!: string;
		public Name!: string;
		public Universe!: number;
		public Description!: string;
		public Type_of_condition!: string;
		public Alternate_names!: string;
		public Transmission!: string;
		public Genetic_factors!: string;
		public Environmental_factors!: string;
		public Lifestyle_factors!: string;
		public Epidemiology!: string;
		public Duration!: string;
		public Variations!: string;
		public Prognosis!: string;
		public Symptoms!: string;
		public Mental_effects!: string;
		public Visual_effects!: string;
		public Prevention!: string;
		public Treatment!: string;
		public Medication!: string;
		public Immunization!: string;
		public Diagnostic_method!: string;
		public Symbolism!: string;
		public Specialty_Field!: string;
		public Rarity!: string;
		public Evolution!: string;
		public Origin!: string;
		public Private_Notes!: string;
		public Notes!: string;
		public created_at!: Date;
		public updated_at!: Date;
		public user_id!: number;
}
