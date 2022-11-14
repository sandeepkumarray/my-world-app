import { isDevMode } from '@angular/core';
import { Observable } from 'rxjs';
import { delay, map } from 'rxjs/operators';
import { environment } from '../../environments/environment';
import { Router } from '@angular/router';
import { JsonPipe } from '@angular/common';
import { ResponseModel } from '../model/ResponseModel'
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams, HttpRequest } from '@angular/common/http';
import { Buildings } from '../model/Buildings'
import { Characters } from '../model/Characters'
import { Conditions } from '../model/Conditions'
import { ContentChangeEvents } from '../model/ContentChangeEvents'
import { Continents } from '../model/Continents'
import { Countries } from '../model/Countries'
import { Creatures } from '../model/Creatures'
import { Deities } from '../model/Deities'
import { Floras } from '../model/Floras'
import { Foods } from '../model/Foods'
import { Governments } from '../model/Governments'
import { Groups } from '../model/Groups'
import { Items } from '../model/Items'
import { Jobs } from '../model/Jobs'
import { Landmarks } from '../model/Landmarks'
import { Languages } from '../model/Languages'
import { Locations } from '../model/Locations'
import { Lores } from '../model/Lores'
import { Magics } from '../model/Magics'
import { Organizations } from '../model/Organizations'
import { Planets } from '../model/Planets'
import { Races } from '../model/Races'
import { Religions } from '../model/Religions'
import { Scenes } from '../model/Scenes'
import { Sports } from '../model/Sports'
import { Technologies } from '../model/Technologies'
import { TimelineEventEntities } from '../model/TimelineEventEntities'
import { TimelineEvents } from '../model/TimelineEvents'
import { Timelines } from '../model/Timelines'
import { Towns } from '../model/Towns'
import { Traditions } from '../model/Traditions'
import { Universes } from '../model/Universes'
import { Vehicles } from '../model/Vehicles'
import { BaseModel } from '../model/BaseModel';

@Injectable({
	providedIn: 'root'
})
export class ContentService {
	constructor(private http: HttpClient, private router: Router) {

	}
	
	getAllContentDataForUser(user_id: any){
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllContentDataForUser&user_id=` + user_id;
		return this.http.get<any>(apiURL);
	}

	getAllContentTypeDataForUser(user_id: any, contentType: any){
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllContentTypeDataForUser&contentType=` + contentType + `&user_id=` + user_id;
		return this.http.get<any>(apiURL);
	}

	getContentDetailsFromTypeID(contentType: any, id: any): Observable<BaseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getContentDetailsFromTypeID&contentType=` + contentType + `&id=` + id;
		return this.http.get<BaseModel>(apiURL);
	}

	saveData(model: BaseModel) {
		let apiURL = `${environment.serviceUrl}api_content.php`;

		model.procedureName = "saveData";

		var jsonData = model;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);
	}

	getBuildings(user_id: any,id: any): Observable<Buildings> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getBuildings&user_id=` + user_id + `&id=` + id;
		return this.http.get<Buildings>(apiURL);

	}

	getAllBuildings(user_id: any): Observable<Buildings[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllBuildings&user_id=` + user_id;
		return this.http.get<Buildings[]>(apiURL);

	}

	addBuilding(buildings: Buildings): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		buildings.procedureName = "addBuilding";
		
		var jsonData = buildings;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteBuilding(buildings: Buildings): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		buildings.procedureName = "deleteBuilding";
		
		var jsonData = buildings;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateBuilding(buildings: Buildings): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		buildings.procedureName = "updateBuilding";
		
		var jsonData = buildings;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getCharacters(user_id: any,id: any): Observable<Characters> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getCharacters&user_id=` + user_id + `&id=` + id;
		return this.http.get<Characters>(apiURL);

	}

	getAllCharacters(user_id: any): Observable<Characters[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllCharacters&user_id=` + user_id;
		return this.http.get<Characters[]>(apiURL);

	}

	addCharacter(characters: Characters): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		characters.procedureName = "addCharacter";
		
		var jsonData = characters;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteCharacter(characters: Characters): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		characters.procedureName = "deleteCharacter";
		
		var jsonData = characters;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateCharacter(characters: Characters): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		characters.procedureName = "updateCharacter";
		
		var jsonData = characters;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getConditions(user_id: any,id: any): Observable<Conditions> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getConditions&user_id=` + user_id + `&id=` + id;
		return this.http.get<Conditions>(apiURL);

	}

	getAllConditions(user_id: any): Observable<Conditions[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllConditions&user_id=` + user_id;
		return this.http.get<Conditions[]>(apiURL);

	}

	addCondition(conditions: Conditions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		conditions.procedureName = "addCondition";
		
		var jsonData = conditions;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteCondition(conditions: Conditions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		conditions.procedureName = "deleteCondition";
		
		var jsonData = conditions;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateCondition(conditions: Conditions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		conditions.procedureName = "updateCondition";
		
		var jsonData = conditions;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getContinents(user_id: any,id: any): Observable<Continents> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getContinents&user_id=` + user_id + `&id=` + id;
		return this.http.get<Continents>(apiURL);

	}

	getAllContinents(user_id: any): Observable<Continents[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllContinents&user_id=` + user_id;
		return this.http.get<Continents[]>(apiURL);

	}

	addContinent(continents: Continents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		continents.procedureName = "addContinent";
		
		var jsonData = continents;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteContinent(continents: Continents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		continents.procedureName = "deleteContinent";
		
		var jsonData = continents;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateContinent(continents: Continents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		continents.procedureName = "updateContinent";
		
		var jsonData = continents;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getCountries(user_id: any,id: any): Observable<Countries> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getCountries&user_id=` + user_id + `&id=` + id;
		return this.http.get<Countries>(apiURL);

	}

	getAllCountries(user_id: any): Observable<Countries[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllCountries&user_id=` + user_id;
		return this.http.get<Countries[]>(apiURL);

	}

	addCountrie(countries: Countries): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		countries.procedureName = "addCountrie";
		
		var jsonData = countries;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteCountrie(countries: Countries): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		countries.procedureName = "deleteCountrie";
		
		var jsonData = countries;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateCountrie(countries: Countries): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		countries.procedureName = "updateCountrie";
		
		var jsonData = countries;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getCreatures(user_id: any,id: any): Observable<Creatures> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getCreatures&user_id=` + user_id + `&id=` + id;
		return this.http.get<Creatures>(apiURL);

	}

	getAllCreatures(user_id: any): Observable<Creatures[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllCreatures&user_id=` + user_id;
		return this.http.get<Creatures[]>(apiURL);

	}

	addCreature(creatures: Creatures): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		creatures.procedureName = "addCreature";
		
		var jsonData = creatures;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteCreature(creatures: Creatures): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		creatures.procedureName = "deleteCreature";
		
		var jsonData = creatures;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateCreature(creatures: Creatures): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		creatures.procedureName = "updateCreature";
		
		var jsonData = creatures;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getDeities(user_id: any,id: any): Observable<Deities> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getDeities&user_id=` + user_id + `&id=` + id;
		return this.http.get<Deities>(apiURL);

	}

	getAllDeities(user_id: any): Observable<Deities[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllDeities&user_id=` + user_id;
		return this.http.get<Deities[]>(apiURL);

	}

	addDeitie(deities: Deities): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		deities.procedureName = "addDeitie";
		
		var jsonData = deities;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteDeitie(deities: Deities): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		deities.procedureName = "deleteDeitie";
		
		var jsonData = deities;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateDeitie(deities: Deities): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		deities.procedureName = "updateDeitie";
		
		var jsonData = deities;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getFloras(user_id: any,id: any): Observable<Floras> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getFloras&user_id=` + user_id + `&id=` + id;
		return this.http.get<Floras>(apiURL);

	}

	getAllFloras(user_id: any): Observable<Floras[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllFloras&user_id=` + user_id;
		return this.http.get<Floras[]>(apiURL);

	}

	addFlora(floras: Floras): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		floras.procedureName = "addFlora";
		
		var jsonData = floras;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteFlora(floras: Floras): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		floras.procedureName = "deleteFlora";
		
		var jsonData = floras;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateFlora(floras: Floras): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		floras.procedureName = "updateFlora";
		
		var jsonData = floras;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getFoods(user_id: any,id: any): Observable<Foods> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getFoods&user_id=` + user_id + `&id=` + id;
		return this.http.get<Foods>(apiURL);

	}

	getAllFoods(user_id: any): Observable<Foods[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllFoods&user_id=` + user_id;
		return this.http.get<Foods[]>(apiURL);

	}

	addFood(foods: Foods): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		foods.procedureName = "addFood";
		
		var jsonData = foods;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteFood(foods: Foods): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		foods.procedureName = "deleteFood";
		
		var jsonData = foods;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateFood(foods: Foods): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		foods.procedureName = "updateFood";
		
		var jsonData = foods;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getGovernments(user_id: any,id: any): Observable<Governments> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getGovernments&user_id=` + user_id + `&id=` + id;
		return this.http.get<Governments>(apiURL);

	}

	getAllGovernments(user_id: any): Observable<Governments[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllGovernments&user_id=` + user_id;
		return this.http.get<Governments[]>(apiURL);

	}

	addGovernment(governments: Governments): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		governments.procedureName = "addGovernment";
		
		var jsonData = governments;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteGovernment(governments: Governments): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		governments.procedureName = "deleteGovernment";
		
		var jsonData = governments;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateGovernment(governments: Governments): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		governments.procedureName = "updateGovernment";
		
		var jsonData = governments;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getGroups(user_id: any,id: any): Observable<Groups> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getGroups&user_id=` + user_id + `&id=` + id;
		return this.http.get<Groups>(apiURL);

	}

	getAllGroups(user_id: any): Observable<Groups[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllGroups&user_id=` + user_id;
		return this.http.get<Groups[]>(apiURL);

	}

	addGroup(groups: Groups): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		groups.procedureName = "addGroup";
		
		var jsonData = groups;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteGroup(groups: Groups): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		groups.procedureName = "deleteGroup";
		
		var jsonData = groups;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateGroup(groups: Groups): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		groups.procedureName = "updateGroup";
		
		var jsonData = groups;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getItems(user_id: any,id: any): Observable<Items> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getItems&user_id=` + user_id + `&id=` + id;
		return this.http.get<Items>(apiURL);

	}

	getAllItems(user_id: any): Observable<Items[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllItems&user_id=` + user_id;
		return this.http.get<Items[]>(apiURL);

	}

	addItem(items: Items): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		items.procedureName = "addItem";
		
		var jsonData = items;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteItem(items: Items): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		items.procedureName = "deleteItem";
		
		var jsonData = items;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateItem(items: Items): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		items.procedureName = "updateItem";
		
		var jsonData = items;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getJobs(user_id: any,id: any): Observable<Jobs> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getJobs&user_id=` + user_id + `&id=` + id;
		return this.http.get<Jobs>(apiURL);

	}

	getAllJobs(user_id: any): Observable<Jobs[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllJobs&user_id=` + user_id;
		return this.http.get<Jobs[]>(apiURL);

	}

	addJob(jobs: Jobs): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		jobs.procedureName = "addJob";
		
		var jsonData = jobs;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteJob(jobs: Jobs): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		jobs.procedureName = "deleteJob";
		
		var jsonData = jobs;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateJob(jobs: Jobs): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		jobs.procedureName = "updateJob";
		
		var jsonData = jobs;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getLandmarks(user_id: any,id: any): Observable<Landmarks> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getLandmarks&user_id=` + user_id + `&id=` + id;
		return this.http.get<Landmarks>(apiURL);

	}

	getAllLandmarks(user_id: any): Observable<Landmarks[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllLandmarks&user_id=` + user_id;
		return this.http.get<Landmarks[]>(apiURL);

	}

	addLandmark(landmarks: Landmarks): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		landmarks.procedureName = "addLandmark";
		
		var jsonData = landmarks;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteLandmark(landmarks: Landmarks): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		landmarks.procedureName = "deleteLandmark";
		
		var jsonData = landmarks;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateLandmark(landmarks: Landmarks): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		landmarks.procedureName = "updateLandmark";
		
		var jsonData = landmarks;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getLanguages(user_id: any,id: any): Observable<Languages> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getLanguages&user_id=` + user_id + `&id=` + id;
		return this.http.get<Languages>(apiURL);

	}

	getAllLanguages(user_id: any): Observable<Languages[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllLanguages&user_id=` + user_id;
		return this.http.get<Languages[]>(apiURL);

	}

	addLanguage(languages: Languages): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		languages.procedureName = "addLanguage";
		
		var jsonData = languages;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteLanguage(languages: Languages): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		languages.procedureName = "deleteLanguage";
		
		var jsonData = languages;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateLanguage(languages: Languages): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		languages.procedureName = "updateLanguage";
		
		var jsonData = languages;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getLocations(user_id: any,id: any): Observable<Locations> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getLocations&user_id=` + user_id + `&id=` + id;
		return this.http.get<Locations>(apiURL);

	}

	getAllLocations(user_id: any): Observable<Locations[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllLocations&user_id=` + user_id;
		return this.http.get<Locations[]>(apiURL);

	}

	addLocation(locations: Locations): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		locations.procedureName = "addLocation";
		
		var jsonData = locations;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteLocation(locations: Locations): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		locations.procedureName = "deleteLocation";
		
		var jsonData = locations;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateLocation(locations: Locations): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		locations.procedureName = "updateLocation";
		
		var jsonData = locations;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getLores(user_id: any,id: any): Observable<Lores> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getLores&user_id=` + user_id + `&id=` + id;
		return this.http.get<Lores>(apiURL);

	}

	getAllLores(user_id: any): Observable<Lores[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllLores&user_id=` + user_id;
		return this.http.get<Lores[]>(apiURL);

	}

	addLore(lores: Lores): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		lores.procedureName = "addLore";
		
		var jsonData = lores;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteLore(lores: Lores): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		lores.procedureName = "deleteLore";
		
		var jsonData = lores;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateLore(lores: Lores): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		lores.procedureName = "updateLore";
		
		var jsonData = lores;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getMagics(user_id: any,id: any): Observable<Magics> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getMagics&user_id=` + user_id + `&id=` + id;
		return this.http.get<Magics>(apiURL);

	}

	getAllMagics(user_id: any): Observable<Magics[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllMagics&user_id=` + user_id;
		return this.http.get<Magics[]>(apiURL);

	}

	addMagic(magics: Magics): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		magics.procedureName = "addMagic";
		
		var jsonData = magics;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteMagic(magics: Magics): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		magics.procedureName = "deleteMagic";
		
		var jsonData = magics;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateMagic(magics: Magics): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		magics.procedureName = "updateMagic";
		
		var jsonData = magics;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getOrganizations(user_id: any,id: any): Observable<Organizations> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getOrganizations&user_id=` + user_id + `&id=` + id;
		return this.http.get<Organizations>(apiURL);

	}

	getAllOrganizations(user_id: any): Observable<Organizations[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllOrganizations&user_id=` + user_id;
		return this.http.get<Organizations[]>(apiURL);

	}

	addOrganization(organizations: Organizations): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		organizations.procedureName = "addOrganization";
		
		var jsonData = organizations;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteOrganization(organizations: Organizations): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		organizations.procedureName = "deleteOrganization";
		
		var jsonData = organizations;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateOrganization(organizations: Organizations): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		organizations.procedureName = "updateOrganization";
		
		var jsonData = organizations;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getPlanets(user_id: any,id: any): Observable<Planets> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getPlanets&user_id=` + user_id + `&id=` + id;
		return this.http.get<Planets>(apiURL);

	}

	getAllPlanets(user_id: any): Observable<Planets[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllPlanets&user_id=` + user_id;
		return this.http.get<Planets[]>(apiURL);

	}

	addPlanet(planets: Planets): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		planets.procedureName = "addPlanet";
		
		var jsonData = planets;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deletePlanet(planets: Planets): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		planets.procedureName = "deletePlanet";
		
		var jsonData = planets;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updatePlanet(planets: Planets): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		planets.procedureName = "updatePlanet";
		
		var jsonData = planets;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getRaces(user_id: any,id: any): Observable<Races> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getRaces&user_id=` + user_id + `&id=` + id;
		return this.http.get<Races>(apiURL);

	}

	getAllRaces(user_id: any): Observable<Races[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllRaces&user_id=` + user_id;
		return this.http.get<Races[]>(apiURL);

	}

	addRace(races: Races): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		races.procedureName = "addRace";
		
		var jsonData = races;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteRace(races: Races): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		races.procedureName = "deleteRace";
		
		var jsonData = races;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateRace(races: Races): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		races.procedureName = "updateRace";
		
		var jsonData = races;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getReligions(user_id: any,id: any): Observable<Religions> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getReligions&user_id=` + user_id + `&id=` + id;
		return this.http.get<Religions>(apiURL);

	}

	getAllReligions(user_id: any): Observable<Religions[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllReligions&user_id=` + user_id;
		return this.http.get<Religions[]>(apiURL);

	}

	addReligion(religions: Religions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		religions.procedureName = "addReligion";
		
		var jsonData = religions;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteReligion(religions: Religions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		religions.procedureName = "deleteReligion";
		
		var jsonData = religions;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateReligion(religions: Religions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		religions.procedureName = "updateReligion";
		
		var jsonData = religions;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getScenes(user_id: any,id: any): Observable<Scenes> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getScenes&user_id=` + user_id + `&id=` + id;
		return this.http.get<Scenes>(apiURL);

	}

	getAllScenes(user_id: any): Observable<Scenes[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllScenes&user_id=` + user_id;
		return this.http.get<Scenes[]>(apiURL);

	}

	addScene(scenes: Scenes): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		scenes.procedureName = "addScene";
		
		var jsonData = scenes;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteScene(scenes: Scenes): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		scenes.procedureName = "deleteScene";
		
		var jsonData = scenes;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateScene(scenes: Scenes): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		scenes.procedureName = "updateScene";
		
		var jsonData = scenes;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getSports(user_id: any,id: any): Observable<Sports> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getSports&user_id=` + user_id + `&id=` + id;
		return this.http.get<Sports>(apiURL);

	}

	getAllSports(user_id: any): Observable<Sports[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllSports&user_id=` + user_id;
		return this.http.get<Sports[]>(apiURL);

	}

	addSport(sports: Sports): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		sports.procedureName = "addSport";
		
		var jsonData = sports;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteSport(sports: Sports): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		sports.procedureName = "deleteSport";
		
		var jsonData = sports;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateSport(sports: Sports): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		sports.procedureName = "updateSport";
		
		var jsonData = sports;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getTechnologies(user_id: any,id: any): Observable<Technologies> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getTechnologies&user_id=` + user_id + `&id=` + id;
		return this.http.get<Technologies>(apiURL);

	}

	getAllTechnologies(user_id: any): Observable<Technologies[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllTechnologies&user_id=` + user_id;
		return this.http.get<Technologies[]>(apiURL);

	}

	addTechnologie(technologies: Technologies): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		technologies.procedureName = "addTechnologie";
		
		var jsonData = technologies;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteTechnologie(technologies: Technologies): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		technologies.procedureName = "deleteTechnologie";
		
		var jsonData = technologies;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateTechnologie(technologies: Technologies): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		technologies.procedureName = "updateTechnologie";
		
		var jsonData = technologies;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getTimelines(user_id: any,id: any): Observable<Timelines> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getTimelines&user_id=` + user_id + `&id=` + id;
		return this.http.get<Timelines>(apiURL);

	}

	getAllTimelines(user_id: any): Observable<Timelines[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllTimelines&user_id=` + user_id;
		return this.http.get<Timelines[]>(apiURL);

	}

	addTimeline(timelines: Timelines): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		timelines.procedureName = "addTimeline";
		
		var jsonData = timelines;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteTimeline(timelines: Timelines): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		timelines.procedureName = "deleteTimeline";
		
		var jsonData = timelines;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateTimeline(timelines: Timelines): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		timelines.procedureName = "updateTimeline";
		
		var jsonData = timelines;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getTowns(user_id: any,id: any): Observable<Towns> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getTowns&user_id=` + user_id + `&id=` + id;
		return this.http.get<Towns>(apiURL);

	}

	getAllTowns(user_id: any): Observable<Towns[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllTowns&user_id=` + user_id;
		return this.http.get<Towns[]>(apiURL);

	}

	addTown(towns: Towns): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		towns.procedureName = "addTown";
		
		var jsonData = towns;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteTown(towns: Towns): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		towns.procedureName = "deleteTown";
		
		var jsonData = towns;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateTown(towns: Towns): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		towns.procedureName = "updateTown";
		
		var jsonData = towns;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getTraditions(user_id: any,id: any): Observable<Traditions> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getTraditions&user_id=` + user_id + `&id=` + id;
		return this.http.get<Traditions>(apiURL);

	}

	getAllTraditions(user_id: any): Observable<Traditions[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllTraditions&user_id=` + user_id;
		return this.http.get<Traditions[]>(apiURL);

	}

	addTradition(traditions: Traditions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		traditions.procedureName = "addTradition";
		
		var jsonData = traditions;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteTradition(traditions: Traditions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		traditions.procedureName = "deleteTradition";
		
		var jsonData = traditions;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateTradition(traditions: Traditions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		traditions.procedureName = "updateTradition";
		
		var jsonData = traditions;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getUniverses(user_id: any,id: any): Observable<Universes> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getUniverses&user_id=` + user_id + `&id=` + id;
		return this.http.get<Universes>(apiURL);

	}

	getAllUniverses(user_id: any): Observable<Universes[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllUniverses&user_id=` + user_id;
		return this.http.get<Universes[]>(apiURL);

	}

	addUniverse(universes: Universes): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		universes.procedureName = "addUniverse";
		
		var jsonData = universes;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteUniverse(universes: Universes): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		universes.procedureName = "deleteUniverse";
		
		var jsonData = universes;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateUniverse(universes: Universes): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		universes.procedureName = "updateUniverse";
		
		var jsonData = universes;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getVehicles(user_id: any,id: any): Observable<Vehicles> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getVehicles&user_id=` + user_id + `&id=` + id;
		return this.http.get<Vehicles>(apiURL);

	}

	getAllVehicles(user_id: any): Observable<Vehicles[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllVehicles&user_id=` + user_id;
		return this.http.get<Vehicles[]>(apiURL);

	}

	addVehicle(vehicles: Vehicles): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		vehicles.procedureName = "addVehicle";
		
		var jsonData = vehicles;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteVehicle(vehicles: Vehicles): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		vehicles.procedureName = "deleteVehicle";
		
		var jsonData = vehicles;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateVehicle(vehicles: Vehicles): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		vehicles.procedureName = "updateVehicle";
		
		var jsonData = vehicles;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}
}
