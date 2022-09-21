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
	constructor(private http: HttpClient,private router: Router) {

	}
	getContentDetailsFromTypeID(contentType: any,id: any): Observable<BaseModel> {
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

	addBuildings(buildings: Buildings): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		buildings.procedureName = "addBuildings";
		
		var jsonData = buildings;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteBuildings(buildings: Buildings): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		buildings.procedureName = "deleteBuildings";
		
		var jsonData = buildings;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateBuildings(buildings: Buildings): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		buildings.procedureName = "updateBuildings";
		
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

	addCharacters(characters: Characters): Observable<ResponseModel> {
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

	deleteCharacters(characters: Characters): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		characters.procedureName = "deleteCharacters";
		
		var jsonData = characters;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateCharacters(characters: Characters): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		characters.procedureName = "updateCharacters";
		
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

	addConditions(conditions: Conditions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		conditions.procedureName = "addConditions";
		
		var jsonData = conditions;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteConditions(conditions: Conditions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		conditions.procedureName = "deleteConditions";
		
		var jsonData = conditions;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateConditions(conditions: Conditions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		conditions.procedureName = "updateConditions";
		
		var jsonData = conditions;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getContentChangeEvents(user_id: any,id: any): Observable<ContentChangeEvents> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getContentChangeEvents&user_id=` + user_id + `&id=` + id;
		return this.http.get<ContentChangeEvents>(apiURL);

	}

	getAllContentChangeEvents(user_id: any): Observable<ContentChangeEvents[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllContentChangeEvents&user_id=` + user_id;
		return this.http.get<ContentChangeEvents[]>(apiURL);

	}

	addContentChangeEvents(contentchangeevents: ContentChangeEvents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		contentchangeevents.procedureName = "addContentChangeEvents";
		
		var jsonData = contentchangeevents;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteContentChangeEvents(contentchangeevents: ContentChangeEvents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		contentchangeevents.procedureName = "deleteContentChangeEvents";
		
		var jsonData = contentchangeevents;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateContentChangeEvents(contentchangeevents: ContentChangeEvents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		contentchangeevents.procedureName = "updateContentChangeEvents";
		
		var jsonData = contentchangeevents;
		
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

	addContinents(continents: Continents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		continents.procedureName = "addContinents";
		
		var jsonData = continents;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteContinents(continents: Continents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		continents.procedureName = "deleteContinents";
		
		var jsonData = continents;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateContinents(continents: Continents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		continents.procedureName = "updateContinents";
		
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

	addCountries(countries: Countries): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		countries.procedureName = "addCountries";
		
		var jsonData = countries;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteCountries(countries: Countries): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		countries.procedureName = "deleteCountries";
		
		var jsonData = countries;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateCountries(countries: Countries): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		countries.procedureName = "updateCountries";
		
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

	addCreatures(creatures: Creatures): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		creatures.procedureName = "addCreatures";
		
		var jsonData = creatures;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteCreatures(creatures: Creatures): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		creatures.procedureName = "deleteCreatures";
		
		var jsonData = creatures;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateCreatures(creatures: Creatures): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		creatures.procedureName = "updateCreatures";
		
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

	addDeities(deities: Deities): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		deities.procedureName = "addDeities";
		
		var jsonData = deities;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteDeities(deities: Deities): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		deities.procedureName = "deleteDeities";
		
		var jsonData = deities;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateDeities(deities: Deities): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		deities.procedureName = "updateDeities";
		
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

	addFloras(floras: Floras): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		floras.procedureName = "addFloras";
		
		var jsonData = floras;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteFloras(floras: Floras): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		floras.procedureName = "deleteFloras";
		
		var jsonData = floras;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateFloras(floras: Floras): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		floras.procedureName = "updateFloras";
		
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

	addFoods(foods: Foods): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		foods.procedureName = "addFoods";
		
		var jsonData = foods;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteFoods(foods: Foods): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		foods.procedureName = "deleteFoods";
		
		var jsonData = foods;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateFoods(foods: Foods): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		foods.procedureName = "updateFoods";
		
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

	addGovernments(governments: Governments): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		governments.procedureName = "addGovernments";
		
		var jsonData = governments;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteGovernments(governments: Governments): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		governments.procedureName = "deleteGovernments";
		
		var jsonData = governments;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateGovernments(governments: Governments): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		governments.procedureName = "updateGovernments";
		
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

	addGroups(groups: Groups): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		groups.procedureName = "addGroups";
		
		var jsonData = groups;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteGroups(groups: Groups): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		groups.procedureName = "deleteGroups";
		
		var jsonData = groups;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateGroups(groups: Groups): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		groups.procedureName = "updateGroups";
		
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

	addItems(items: Items): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		items.procedureName = "addItems";
		
		var jsonData = items;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteItems(items: Items): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		items.procedureName = "deleteItems";
		
		var jsonData = items;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateItems(items: Items): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		items.procedureName = "updateItems";
		
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

	addJobs(jobs: Jobs): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		jobs.procedureName = "addJobs";
		
		var jsonData = jobs;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteJobs(jobs: Jobs): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		jobs.procedureName = "deleteJobs";
		
		var jsonData = jobs;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateJobs(jobs: Jobs): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		jobs.procedureName = "updateJobs";
		
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

	addLandmarks(landmarks: Landmarks): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		landmarks.procedureName = "addLandmarks";
		
		var jsonData = landmarks;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteLandmarks(landmarks: Landmarks): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		landmarks.procedureName = "deleteLandmarks";
		
		var jsonData = landmarks;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateLandmarks(landmarks: Landmarks): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		landmarks.procedureName = "updateLandmarks";
		
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

	addLanguages(languages: Languages): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		languages.procedureName = "addLanguages";
		
		var jsonData = languages;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteLanguages(languages: Languages): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		languages.procedureName = "deleteLanguages";
		
		var jsonData = languages;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateLanguages(languages: Languages): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		languages.procedureName = "updateLanguages";
		
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

	addLocations(locations: Locations): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		locations.procedureName = "addLocations";
		
		var jsonData = locations;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteLocations(locations: Locations): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		locations.procedureName = "deleteLocations";
		
		var jsonData = locations;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateLocations(locations: Locations): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		locations.procedureName = "updateLocations";
		
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

	addLores(lores: Lores): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		lores.procedureName = "addLores";
		
		var jsonData = lores;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteLores(lores: Lores): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		lores.procedureName = "deleteLores";
		
		var jsonData = lores;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateLores(lores: Lores): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		lores.procedureName = "updateLores";
		
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

	addMagics(magics: Magics): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		magics.procedureName = "addMagics";
		
		var jsonData = magics;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteMagics(magics: Magics): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		magics.procedureName = "deleteMagics";
		
		var jsonData = magics;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateMagics(magics: Magics): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		magics.procedureName = "updateMagics";
		
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

	addOrganizations(organizations: Organizations): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		organizations.procedureName = "addOrganizations";
		
		var jsonData = organizations;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteOrganizations(organizations: Organizations): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		organizations.procedureName = "deleteOrganizations";
		
		var jsonData = organizations;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateOrganizations(organizations: Organizations): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		organizations.procedureName = "updateOrganizations";
		
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

	addPlanets(planets: Planets): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		planets.procedureName = "addPlanets";
		
		var jsonData = planets;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deletePlanets(planets: Planets): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		planets.procedureName = "deletePlanets";
		
		var jsonData = planets;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updatePlanets(planets: Planets): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		planets.procedureName = "updatePlanets";
		
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

	addRaces(races: Races): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		races.procedureName = "addRaces";
		
		var jsonData = races;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteRaces(races: Races): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		races.procedureName = "deleteRaces";
		
		var jsonData = races;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateRaces(races: Races): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		races.procedureName = "updateRaces";
		
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

	addReligions(religions: Religions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		religions.procedureName = "addReligions";
		
		var jsonData = religions;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteReligions(religions: Religions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		religions.procedureName = "deleteReligions";
		
		var jsonData = religions;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateReligions(religions: Religions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		religions.procedureName = "updateReligions";
		
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

	addScenes(scenes: Scenes): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		scenes.procedureName = "addScenes";
		
		var jsonData = scenes;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteScenes(scenes: Scenes): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		scenes.procedureName = "deleteScenes";
		
		var jsonData = scenes;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateScenes(scenes: Scenes): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		scenes.procedureName = "updateScenes";
		
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

	addSports(sports: Sports): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		sports.procedureName = "addSports";
		
		var jsonData = sports;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteSports(sports: Sports): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		sports.procedureName = "deleteSports";
		
		var jsonData = sports;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateSports(sports: Sports): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		sports.procedureName = "updateSports";
		
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

	addTechnologies(technologies: Technologies): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		technologies.procedureName = "addTechnologies";
		
		var jsonData = technologies;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteTechnologies(technologies: Technologies): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		technologies.procedureName = "deleteTechnologies";
		
		var jsonData = technologies;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateTechnologies(technologies: Technologies): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		technologies.procedureName = "updateTechnologies";
		
		var jsonData = technologies;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getTimelineEventEntities(user_id: any,id: any): Observable<TimelineEventEntities> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getTimelineEventEntities&user_id=` + user_id + `&id=` + id;
		return this.http.get<TimelineEventEntities>(apiURL);

	}

	getAllTimelineEventEntities(user_id: any): Observable<TimelineEventEntities[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllTimelineEventEntities&user_id=` + user_id;
		return this.http.get<TimelineEventEntities[]>(apiURL);

	}

	addTimelineEventEntities(timelineevententities: TimelineEventEntities): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		timelineevententities.procedureName = "addTimelineEventEntities";
		
		var jsonData = timelineevententities;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteTimelineEventEntities(timelineevententities: TimelineEventEntities): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		timelineevententities.procedureName = "deleteTimelineEventEntities";
		
		var jsonData = timelineevententities;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateTimelineEventEntities(timelineevententities: TimelineEventEntities): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		timelineevententities.procedureName = "updateTimelineEventEntities";
		
		var jsonData = timelineevententities;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getTimelineEvents(user_id: any,id: any): Observable<TimelineEvents> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getTimelineEvents&user_id=` + user_id + `&id=` + id;
		return this.http.get<TimelineEvents>(apiURL);

	}

	getAllTimelineEvents(user_id: any): Observable<TimelineEvents[]> {
		let apiURL = `${environment.serviceUrl}api_content.php?procedureName=getAllTimelineEvents&user_id=` + user_id;
		return this.http.get<TimelineEvents[]>(apiURL);

	}

	addTimelineEvents(timelineevents: TimelineEvents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		timelineevents.procedureName = "addTimelineEvents";
		
		var jsonData = timelineevents;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteTimelineEvents(timelineevents: TimelineEvents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		timelineevents.procedureName = "deleteTimelineEvents";
		
		var jsonData = timelineevents;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateTimelineEvents(timelineevents: TimelineEvents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		timelineevents.procedureName = "updateTimelineEvents";
		
		var jsonData = timelineevents;
		
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

	addTimelines(timelines: Timelines): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		timelines.procedureName = "addTimelines";
		
		var jsonData = timelines;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteTimelines(timelines: Timelines): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		timelines.procedureName = "deleteTimelines";
		
		var jsonData = timelines;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateTimelines(timelines: Timelines): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		timelines.procedureName = "updateTimelines";
		
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

	addTowns(towns: Towns): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		towns.procedureName = "addTowns";
		
		var jsonData = towns;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteTowns(towns: Towns): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		towns.procedureName = "deleteTowns";
		
		var jsonData = towns;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateTowns(towns: Towns): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		towns.procedureName = "updateTowns";
		
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

	addTraditions(traditions: Traditions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		traditions.procedureName = "addTraditions";
		
		var jsonData = traditions;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteTraditions(traditions: Traditions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		traditions.procedureName = "deleteTraditions";
		
		var jsonData = traditions;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateTraditions(traditions: Traditions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		traditions.procedureName = "updateTraditions";
		
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

	addUniverses(universes: Universes): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		universes.procedureName = "addUniverses";
		
		var jsonData = universes;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteUniverses(universes: Universes): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		universes.procedureName = "deleteUniverses";
		
		var jsonData = universes;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateUniverses(universes: Universes): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		universes.procedureName = "updateUniverses";
		
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

	addVehicles(vehicles: Vehicles): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		vehicles.procedureName = "addVehicles";
		
		var jsonData = vehicles;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteVehicles(vehicles: Vehicles): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		vehicles.procedureName = "deleteVehicles";
		
		var jsonData = vehicles;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateVehicles(vehicles: Vehicles): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_content.php`;
		
		
		vehicles.procedureName = "updateVehicles";
		
		var jsonData = vehicles;
		
		const httpOptions = {
		  headers: new HttpHeaders({
			'Content-Type': 'application/json'
		  })
		};
		
		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

}
