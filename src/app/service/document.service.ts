import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { delay, map } from 'rxjs/operators';
import { environment } from '../../environments/environment';
import { Router } from '@angular/router';
import { JsonPipe } from '@angular/common';
import { ResponseModel } from '../model/ResponseModel'
import { HttpClient, HttpHeaders, HttpParams, HttpRequest } from '@angular/common/http';
import { DashboardRecentModel } from '../model/DashboardRecentModel';
import { Documents, Folders } from '../model';

@Injectable({
	providedIn: 'root'
})
export class DocumentService {

	constructor(private http: HttpClient, private router: Router) {

	}

	UpdateChildFoldersToTop(folder_id: any) {
		let apiURL = `${environment.serviceUrl}api_document.php`;

		let json_object: any = {};
		json_object.procedureName = "UpdateChildFoldersToTop";
		json_object.folder_id = folder_id;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: json_object }, httpOptions);
	}

	updateDocumentsFolderToNull(folder_id: any) {
		let apiURL = `${environment.serviceUrl}api_document.php`;

		let json_object: any = {};
		json_object.procedureName = "updateDocumentsFolderToNull";
		json_object.folder_id = folder_id;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: json_object }, httpOptions);
	}

	getAllChildFolders(user_id: any, folder_id: any): Observable<Folders[]> {
		let apiURL = `${environment.serviceUrl}api_document.php?procedureName=GetAllChildFolders&user_id=` + user_id + `&folder_id=` + folder_id;
		return this.http.get<Folders[]>(apiURL);

	}

	getAllMentions(user_id: any): Observable<DashboardRecentModel[]> {
		let apiURL = `${environment.serviceUrl}api_document.php?procedureName=getAllMentions&user_id=` + user_id;
		return this.http.get<DashboardRecentModel[]>(apiURL);

	}

	getAllDocuments(user_id: any): Observable<Documents[]> {
		let apiURL = `${environment.serviceUrl}api_document.php?procedureName=getAllDocuments&user_id=` + user_id;
		return this.http.get<Documents[]>(apiURL);
	}

	getAllDocumentsForFolderId(user_id: any, folder_id: any): Observable<Documents[]> {
		let apiURL = `${environment.serviceUrl}api_document.php?procedureName=getAllDocumentsForFolderId&user_id=` + user_id + `&folder_id=` + folder_id;
		return this.http.get<Documents[]>(apiURL);
	}

	getDocuments(user_id: any, id: any): Observable<Documents> {
		let apiURL = `${environment.serviceUrl}api_document.php?procedureName=getDocuments&user_id=` + user_id + `&id=` + id;
		return this.http.get<Documents>(apiURL);

	}

	addDocuments(documents: Documents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_document.php`;


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
		let apiURL = `${environment.serviceUrl}api_document.php`;


		documents.procedureName = "deleteDocument";

		var jsonData = documents;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	updateDocuments(documents: Documents): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_document.php`;


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
		let apiURL = `${environment.serviceUrl}api_document.php?procedureName=getFolders&user_id=` + user_id + `&id=` + id;
		return this.http.get<Folders>(apiURL);

	}

	getAllFolders(user_id: any): Observable<Folders[]> {
		let apiURL = `${environment.serviceUrl}api_document.php?procedureName=getAllFolders&user_id=` + user_id;
		return this.http.get<Folders[]>(apiURL);

	}

	getAllParentFolders(user_id: any): Observable<Folders[]> {
		let apiURL = `${environment.serviceUrl}api_document.php?procedureName=getAllParentFolders&user_id=` + user_id;
		return this.http.get<Folders[]>(apiURL);

	}

	addFolders(folders: Folders): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_document.php`;


		folders.procedureName = "addFolder";

		var jsonData = folders;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

	deleteFolders(folders: Folders): Observable<ResponseModel> {
		let apiURL = `${environment.serviceUrl}api_document.php`;


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
		let apiURL = `${environment.serviceUrl}api_document.php`;


		folders.procedureName = "updateFolders";

		var jsonData = folders;

		const httpOptions = {
			headers: new HttpHeaders({
				'Content-Type': 'application/json'
			})
		};

		return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);

	}

}
