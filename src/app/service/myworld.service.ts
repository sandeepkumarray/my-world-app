import { isDevMode } from '@angular/core';
import { Observable } from 'rxjs';
import { delay, map } from 'rxjs/operators';
import { environment } from '../../environments/environment';
import { Router } from '@angular/router';
import { JsonPipe } from '@angular/common';
import { ResponseModel } from '../model/ResponseModel'
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams, HttpRequest } from '@angular/common/http';
import { AppConfig } from '../model/AppConfig'
import { UserContentTemplate } from '../model/UserContentTemplate'
import { UserContentBucket } from '../model/UserContentBucket'
import { ObjectStorageKeys } from '../model/ObjectStorageKeys'
import { ContentObjectAttachment } from '../model/ContentObjectAttachment'
import { ContentObject } from '../model/ContentObject'
import { CharacterBirthtowns } from '../model/CharacterBirthtowns'
import { CharacterCompanions } from '../model/CharacterCompanions'
import { CharacterEnemies } from '../model/CharacterEnemies'
import { CharacterFloras } from '../model/CharacterFloras'
import { CharacterFriends } from '../model/CharacterFriends'
import { CharacterItems } from '../model/CharacterItems'
import { CharacterLoveInterests } from '../model/CharacterLoveInterests'
import { CharacterMagics } from '../model/CharacterMagics'
import { CharacterTechnologies } from '../model/CharacterTechnologies'
import { ContentPlans } from '../model/ContentPlans'
import { ContentTypes } from '../model/ContentTypes'
import { Documents } from '../model/Documents'
import { Folders } from '../model/Folders'
import { UserDetails } from '../model/UserDetails'
import { UserPlan } from '../model/UserPlan'
import { Users } from '../model/Users'
import { ContentTemplateModel } from '../model/ContentTemplateModel';
import { ContentBlobObject } from '../model/ContentBlobObject';
import { DashboardRecentModel } from '../model/DashboardRecentModel';

@Injectable({
	providedIn: 'root'
})
export class MyworldService {


	constructor(private http: HttpClient, private router: Router) {

	}

	getRecents(user_id: any): Observable<DashboardRecentModel[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getRecents&user_id=` + user_id;

		return this.http.get<DashboardRecentModel[]>(apiURL);
	}

	createContent(content_type: string, jsonString:string, user_id: string | undefined){
		let apiURL = `${environment.serviceUrl}api_myworld.php`;
		let json_object: any = {};

		json_object.procedureName = "createContent";
		json_object.content_type = content_type.toLowerCase();
		json_object.jsonData = jsonString;
		json_object.user_id = user_id;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: json_object }, httpOptions);
	}

	createItem(content_type: string, user_id: string | undefined) {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;
		let json_object: any = {};

		json_object.procedureName = "createItem";
		json_object.content_type = content_type.toLowerCase();
		json_object.user_id = user_id;
		json_object.name = "New " + content_type;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: json_object }, httpOptions);
	}

	getDashboard(user_id: any): Observable<any> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getDashboard&user_id=` + user_id;

		return this.http.get<ContentPlans>(apiURL);
	}

	getUserContentPlans(user_id: any): Observable<ContentPlans> {

		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getUserContentPlans&user_id=` + user_id;

		return this.http.get<ContentPlans>(apiURL);
	}

	deleteContentBlobObject(object_id: any) {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;
		let json_object: any = {};

		json_object.procedureName = "deleteContentBlobObject";
		json_object.object_id = object_id;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: json_object }, httpOptions);
	}

	getContentTypeCardImage(name: any): Observable<any> {

		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getContentTypeCardImage&name=` + name.tolowercase();

		return this.http.get(apiURL);
	}

	addImageToContent(file: ContentBlobObject) {

		let apiURL = `${environment.fileUploadUrl}`;

		let formData: FormData = new FormData();
		formData.append('object_blob', file.object_blob, file.object_name);
		formData.append('object_size', file.object_size!.toString());
		formData.append('content_id', file.content_id!);
		formData.append('content_type', file.content_type!);

		console.log(file.content_type);

		let headers = new Headers();
		headers.append('enctype', 'multipart/form-data');
		headers.append('Accept', 'application/json');

		let params = new HttpParams();

		const options = {
			params: params,
			reportProgress: true,
		};

		const req = new HttpRequest('POST', apiURL, formData, options);
		return this.http.request(req);
	}

	getContentBlobObject(content_id: any, content_type: any): Observable<ContentBlobObject[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getContentBlobObject&content_type=` + content_type + `&content_id=` + content_id;
		return this.http.get<ContentBlobObject[]>(apiURL);

	}

	getUsersContentTemplate(user_id: any): Observable<any> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getUsersContentTemplate&user_id=` + user_id;
		return this.http.get<any>(apiURL);
	}

	getAppConfig(user_id: any, id: any): Observable<AppConfig> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAppConfig&user_id=` + user_id + `&id=` + id;
		return this.http.get<AppConfig>(apiURL);

	}

	getAllAppConfig(user_id: any): Observable<AppConfig[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllAppConfig&user_id=` + user_id;
		return this.http.get<AppConfig[]>(apiURL);

	}

	addAppConfig(appconfig: AppConfig): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		appconfig.procedureName = "addAppConfig";

		var jsonData = appconfig;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteAppConfig(appconfig: AppConfig): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		appconfig.procedureName = "deleteAppConfig";

		var jsonData = appconfig;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateAppConfig(appconfig: AppConfig): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		appconfig.procedureName = "updateAppConfig";

		var jsonData = appconfig;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getUserContentTemplate(user_id: any, id: any): Observable<UserContentTemplate> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getUserContentTemplate&user_id=` + user_id + `&id=` + id;
		return this.http.get<UserContentTemplate>(apiURL);

	}

	getAllUserContentTemplate(user_id: any): Observable<UserContentTemplate[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllUserContentTemplate&user_id=` + user_id;
		return this.http.get<UserContentTemplate[]>(apiURL);

	}

	addUserContentTemplate(usercontenttemplate: UserContentTemplate): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		usercontenttemplate.procedureName = "addUserContentTemplate";

		var jsonData = usercontenttemplate;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteUserContentTemplate(usercontenttemplate: UserContentTemplate): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		usercontenttemplate.procedureName = "deleteUserContentTemplate";

		var jsonData = usercontenttemplate;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateUserContentTemplate(usercontenttemplate: UserContentTemplate): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		usercontenttemplate.procedureName = "updateUserContentTemplate";

		var jsonData = usercontenttemplate;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getUserContentBucket(user_id: any): Observable<UserContentBucket> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getUserContentBucket&user_id=` + user_id;
		return this.http.get<UserContentBucket>(apiURL);

	}

	getAllUserContentBucket(user_id: any): Observable<UserContentBucket[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllUserContentBucket&user_id=` + user_id;
		return this.http.get<UserContentBucket[]>(apiURL);

	}

	addUserContentBucket(usercontentbucket: UserContentBucket): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		usercontentbucket.procedureName = "addUserContentBucket";

		var jsonData = usercontentbucket;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteUserContentBucket(usercontentbucket: UserContentBucket): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		usercontentbucket.procedureName = "deleteUserContentBucket";

		var jsonData = usercontentbucket;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateUserContentBucket(usercontentbucket: UserContentBucket): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		usercontentbucket.procedureName = "updateUserContentBucket";

		var jsonData = usercontentbucket;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getObjectStorageKeys(user_id: any, id: any): Observable<ObjectStorageKeys> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getObjectStorageKeys&user_id=` + user_id + `&id=` + id;
		return this.http.get<ObjectStorageKeys>(apiURL);

	}

	getAllObjectStorageKeys(user_id: any): Observable<ObjectStorageKeys[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllObjectStorageKeys&user_id=` + user_id;
		return this.http.get<ObjectStorageKeys[]>(apiURL);

	}

	addObjectStorageKeys(objectstoragekeys: ObjectStorageKeys): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		objectstoragekeys.procedureName = "addObjectStorageKeys";

		var jsonData = objectstoragekeys;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteObjectStorageKeys(objectstoragekeys: ObjectStorageKeys): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		objectstoragekeys.procedureName = "deleteObjectStorageKeys";

		var jsonData = objectstoragekeys;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateObjectStorageKeys(objectstoragekeys: ObjectStorageKeys): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		objectstoragekeys.procedureName = "updateObjectStorageKeys";

		var jsonData = objectstoragekeys;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getContentObjectAttachment(user_id: any, id: any): Observable<ContentObjectAttachment> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getContentObjectAttachment&user_id=` + user_id + `&id=` + id;
		return this.http.get<ContentObjectAttachment>(apiURL);

	}

	getAllContentObjectAttachment(content_id: any, content_type: any): Observable<ContentObject[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllContentObjectAttachment&content_id=` + content_id + `&content_type=` + content_type;
		return this.http.get<ContentObject[]>(apiURL);

	}

	addContentObjectAttachment(contentobjectattachment: ContentObjectAttachment): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		contentobjectattachment.procedureName = "addContentObjectAttachment";

		var jsonData = contentobjectattachment;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteContentObjectAttachment(contentobjectattachment: ContentObjectAttachment): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		contentobjectattachment.procedureName = "deleteContentObjectAttachment";

		var jsonData = contentobjectattachment;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateContentObjectAttachment(contentobjectattachment: ContentObjectAttachment): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		contentobjectattachment.procedureName = "updateContentObjectAttachment";

		var jsonData = contentobjectattachment;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getContentObject(user_id: any, id: any): Observable<ContentObject> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getContentObject&user_id=` + user_id + `&id=` + id;
		return this.http.get<ContentObject>(apiURL);

	}

	getAllContentObject(user_id: any): Observable<ContentObject[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllContentObject&user_id=` + user_id;
		return this.http.get<ContentObject[]>(apiURL);

	}

	addContentObject(contentobject: ContentObject): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		contentobject.procedureName = "addContentObject";

		var jsonData = contentobject;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteContentObject(contentobject: ContentObject): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		contentobject.procedureName = "deleteContentObject";

		var jsonData = contentobject;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateContentObject(contentobject: ContentObject): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		contentobject.procedureName = "updateContentObject";

		var jsonData = contentobject;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getCharacterBirthtowns(user_id: any, id: any): Observable<CharacterBirthtowns> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getCharacterBirthtowns&user_id=` + user_id + `&id=` + id;
		return this.http.get<CharacterBirthtowns>(apiURL);

	}

	getAllCharacterBirthtowns(user_id: any): Observable<CharacterBirthtowns[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllCharacterBirthtowns&user_id=` + user_id;
		return this.http.get<CharacterBirthtowns[]>(apiURL);

	}

	addCharacterBirthtowns(characterbirthtowns: CharacterBirthtowns): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characterbirthtowns.procedureName = "addCharacterBirthtowns";

		var jsonData = characterbirthtowns;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteCharacterBirthtowns(characterbirthtowns: CharacterBirthtowns): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characterbirthtowns.procedureName = "deleteCharacterBirthtowns";

		var jsonData = characterbirthtowns;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateCharacterBirthtowns(characterbirthtowns: CharacterBirthtowns): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characterbirthtowns.procedureName = "updateCharacterBirthtowns";

		var jsonData = characterbirthtowns;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getCharacterCompanions(user_id: any, id: any): Observable<CharacterCompanions> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getCharacterCompanions&user_id=` + user_id + `&id=` + id;
		return this.http.get<CharacterCompanions>(apiURL);

	}

	getAllCharacterCompanions(user_id: any): Observable<CharacterCompanions[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllCharacterCompanions&user_id=` + user_id;
		return this.http.get<CharacterCompanions[]>(apiURL);

	}

	addCharacterCompanions(charactercompanions: CharacterCompanions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		charactercompanions.procedureName = "addCharacterCompanions";

		var jsonData = charactercompanions;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteCharacterCompanions(charactercompanions: CharacterCompanions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		charactercompanions.procedureName = "deleteCharacterCompanions";

		var jsonData = charactercompanions;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateCharacterCompanions(charactercompanions: CharacterCompanions): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		charactercompanions.procedureName = "updateCharacterCompanions";

		var jsonData = charactercompanions;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getCharacterEnemies(user_id: any, id: any): Observable<CharacterEnemies> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getCharacterEnemies&user_id=` + user_id + `&id=` + id;
		return this.http.get<CharacterEnemies>(apiURL);

	}

	getAllCharacterEnemies(user_id: any): Observable<CharacterEnemies[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllCharacterEnemies&user_id=` + user_id;
		return this.http.get<CharacterEnemies[]>(apiURL);

	}

	addCharacterEnemies(characterenemies: CharacterEnemies): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characterenemies.procedureName = "addCharacterEnemies";

		var jsonData = characterenemies;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteCharacterEnemies(characterenemies: CharacterEnemies): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characterenemies.procedureName = "deleteCharacterEnemies";

		var jsonData = characterenemies;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateCharacterEnemies(characterenemies: CharacterEnemies): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characterenemies.procedureName = "updateCharacterEnemies";

		var jsonData = characterenemies;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getCharacterFloras(user_id: any, id: any): Observable<CharacterFloras> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getCharacterFloras&user_id=` + user_id + `&id=` + id;
		return this.http.get<CharacterFloras>(apiURL);

	}

	getAllCharacterFloras(user_id: any): Observable<CharacterFloras[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllCharacterFloras&user_id=` + user_id;
		return this.http.get<CharacterFloras[]>(apiURL);

	}

	addCharacterFloras(characterfloras: CharacterFloras): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characterfloras.procedureName = "addCharacterFloras";

		var jsonData = characterfloras;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteCharacterFloras(characterfloras: CharacterFloras): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characterfloras.procedureName = "deleteCharacterFloras";

		var jsonData = characterfloras;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateCharacterFloras(characterfloras: CharacterFloras): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characterfloras.procedureName = "updateCharacterFloras";

		var jsonData = characterfloras;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getCharacterFriends(user_id: any, id: any): Observable<CharacterFriends> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getCharacterFriends&user_id=` + user_id + `&id=` + id;
		return this.http.get<CharacterFriends>(apiURL);

	}

	getAllCharacterFriends(user_id: any): Observable<CharacterFriends[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllCharacterFriends&user_id=` + user_id;
		return this.http.get<CharacterFriends[]>(apiURL);

	}

	addCharacterFriends(characterfriends: CharacterFriends): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characterfriends.procedureName = "addCharacterFriends";

		var jsonData = characterfriends;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteCharacterFriends(characterfriends: CharacterFriends): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characterfriends.procedureName = "deleteCharacterFriends";

		var jsonData = characterfriends;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateCharacterFriends(characterfriends: CharacterFriends): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characterfriends.procedureName = "updateCharacterFriends";

		var jsonData = characterfriends;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getCharacterItems(user_id: any, id: any): Observable<CharacterItems> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getCharacterItems&user_id=` + user_id + `&id=` + id;
		return this.http.get<CharacterItems>(apiURL);

	}

	getAllCharacterItems(user_id: any): Observable<CharacterItems[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllCharacterItems&user_id=` + user_id;
		return this.http.get<CharacterItems[]>(apiURL);

	}

	addCharacterItems(characteritems: CharacterItems): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characteritems.procedureName = "addCharacterItems";

		var jsonData = characteritems;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteCharacterItems(characteritems: CharacterItems): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characteritems.procedureName = "deleteCharacterItems";

		var jsonData = characteritems;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateCharacterItems(characteritems: CharacterItems): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characteritems.procedureName = "updateCharacterItems";

		var jsonData = characteritems;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getCharacterLoveInterests(user_id: any, id: any): Observable<CharacterLoveInterests> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getCharacterLoveInterests&user_id=` + user_id + `&id=` + id;
		return this.http.get<CharacterLoveInterests>(apiURL);

	}

	getAllCharacterLoveInterests(user_id: any): Observable<CharacterLoveInterests[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllCharacterLoveInterests&user_id=` + user_id;
		return this.http.get<CharacterLoveInterests[]>(apiURL);

	}

	addCharacterLoveInterests(characterloveinterests: CharacterLoveInterests): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characterloveinterests.procedureName = "addCharacterLoveInterests";

		var jsonData = characterloveinterests;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteCharacterLoveInterests(characterloveinterests: CharacterLoveInterests): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characterloveinterests.procedureName = "deleteCharacterLoveInterests";

		var jsonData = characterloveinterests;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateCharacterLoveInterests(characterloveinterests: CharacterLoveInterests): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		characterloveinterests.procedureName = "updateCharacterLoveInterests";

		var jsonData = characterloveinterests;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getCharacterMagics(user_id: any, id: any): Observable<CharacterMagics> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getCharacterMagics&user_id=` + user_id + `&id=` + id;
		return this.http.get<CharacterMagics>(apiURL);

	}

	getAllCharacterMagics(user_id: any): Observable<CharacterMagics[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllCharacterMagics&user_id=` + user_id;
		return this.http.get<CharacterMagics[]>(apiURL);

	}

	addCharacterMagics(charactermagics: CharacterMagics): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		charactermagics.procedureName = "addCharacterMagics";

		var jsonData = charactermagics;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteCharacterMagics(charactermagics: CharacterMagics): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		charactermagics.procedureName = "deleteCharacterMagics";

		var jsonData = charactermagics;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateCharacterMagics(charactermagics: CharacterMagics): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		charactermagics.procedureName = "updateCharacterMagics";

		var jsonData = charactermagics;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getCharacterTechnologies(user_id: any, id: any): Observable<CharacterTechnologies> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getCharacterTechnologies&user_id=` + user_id + `&id=` + id;
		return this.http.get<CharacterTechnologies>(apiURL);

	}

	getAllCharacterTechnologies(user_id: any): Observable<CharacterTechnologies[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllCharacterTechnologies&user_id=` + user_id;
		return this.http.get<CharacterTechnologies[]>(apiURL);

	}

	addCharacterTechnologies(charactertechnologies: CharacterTechnologies): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		charactertechnologies.procedureName = "addCharacterTechnologies";

		var jsonData = charactertechnologies;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteCharacterTechnologies(charactertechnologies: CharacterTechnologies): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		charactertechnologies.procedureName = "deleteCharacterTechnologies";

		var jsonData = charactertechnologies;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateCharacterTechnologies(charactertechnologies: CharacterTechnologies): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		charactertechnologies.procedureName = "updateCharacterTechnologies";

		var jsonData = charactertechnologies;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getContentPlans(user_id: any, id: any): Observable<ContentPlans> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getContentPlans&user_id=` + user_id + `&id=` + id;
		return this.http.get<ContentPlans>(apiURL);

	}

	getAllContentPlans(user_id: any): Observable<ContentPlans[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllContentPlans&user_id=` + user_id;
		return this.http.get<ContentPlans[]>(apiURL);

	}

	addContentPlans(contentplans: ContentPlans): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		contentplans.procedureName = "addContentPlans";

		var jsonData = contentplans;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteContentPlans(contentplans: ContentPlans): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		contentplans.procedureName = "deleteContentPlans";

		var jsonData = contentplans;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateContentPlans(contentplans: ContentPlans): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		contentplans.procedureName = "updateContentPlans";

		var jsonData = contentplans;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getContentTypes(name: any): Observable<ContentTypes> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getContentTypes&name=` + name;
		return this.http.get<ContentTypes>(apiURL);

	}

	getAllContentTypes(): Observable<ContentTypes[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllContentTypes`;
		return this.http.get<ContentTypes[]>(apiURL);

	}

	addContentTypes(contenttypes: ContentTypes): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		contenttypes.procedureName = "addContentTypes";

		var jsonData = contenttypes;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteContentTypes(contenttypes: ContentTypes): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		contenttypes.procedureName = "deleteContentTypes";

		var jsonData = contenttypes;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateContentTypes(contenttypes: ContentTypes): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		contenttypes.procedureName = "updateContentTypes";

		var jsonData = contenttypes;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getDocuments(user_id: any, id: any): Observable<Documents> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getDocuments&user_id=` + user_id + `&id=` + id;
		return this.http.get<Documents>(apiURL);

	}

	getAllDocuments(user_id: any): Observable<Documents[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllDocuments&user_id=` + user_id;
		return this.http.get<Documents[]>(apiURL);

	}

	addDocuments(documents: Documents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		documents.procedureName = "addDocuments";

		var jsonData = documents;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteDocuments(documents: Documents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		documents.procedureName = "deleteDocuments";

		var jsonData = documents;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateDocuments(documents: Documents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		documents.procedureName = "updateDocuments";

		var jsonData = documents;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getFolders(user_id: any, id: any): Observable<Folders> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getFolders&user_id=` + user_id + `&id=` + id;
		return this.http.get<Folders>(apiURL);

	}

	getAllFolders(user_id: any): Observable<Folders[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllFolders&user_id=` + user_id;
		return this.http.get<Folders[]>(apiURL);

	}

	addFolders(folders: Folders): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		folders.procedureName = "addFolders";

		var jsonData = folders;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteFolders(folders: Folders): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		folders.procedureName = "deleteFolders";

		var jsonData = folders;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateFolders(folders: Folders): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		folders.procedureName = "updateFolders";

		var jsonData = folders;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getUserDetails(user_id: any, id: any): Observable<UserDetails> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getUserDetails&user_id=` + user_id + `&id=` + id;
		return this.http.get<UserDetails>(apiURL);

	}

	getAllUserDetails(user_id: any): Observable<UserDetails[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllUserDetails&user_id=` + user_id;
		return this.http.get<UserDetails[]>(apiURL);

	}

	addUserDetails(userdetails: UserDetails): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		userdetails.procedureName = "addUserDetails";

		var jsonData = userdetails;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteUserDetails(userdetails: UserDetails): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		userdetails.procedureName = "deleteUserDetails";

		var jsonData = userdetails;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateUserDetails(userdetails: UserDetails): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		userdetails.procedureName = "updateUserDetails";

		var jsonData = userdetails;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getUserPlan(user_id: any, id: any): Observable<UserPlan> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getUserPlan&user_id=` + user_id + `&id=` + id;
		return this.http.get<UserPlan>(apiURL);

	}

	getAllUserPlan(user_id: any): Observable<UserPlan[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllUserPlan&user_id=` + user_id;
		return this.http.get<UserPlan[]>(apiURL);

	}

	addUserPlan(userplan: UserPlan): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		userplan.procedureName = "addUserPlan";

		var jsonData = userplan;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteUserPlan(userplan: UserPlan): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		userplan.procedureName = "deleteUserPlan";

		var jsonData = userplan;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateUserPlan(userplan: UserPlan): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		userplan.procedureName = "updateUserPlan";

		var jsonData = userplan;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	getUsers(user_id: any, id: any): Observable<Users> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getUsers&user_id=` + user_id + `&id=` + id;
		return this.http.get<Users>(apiURL);

	}

	getAllUsers(user_id: any): Observable<Users[]> {
		let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllUsers&user_id=` + user_id;
		return this.http.get<Users[]>(apiURL);

	}

	addUsers(users: Users): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		users.procedureName = "addUsers";

		var jsonData = users;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteUsers(users: Users): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		users.procedureName = "deleteUsers";

		var jsonData = users;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateUsers(users: Users): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_myworld.php`;


		users.procedureName = "updateUsers";

		var jsonData = users;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

}
