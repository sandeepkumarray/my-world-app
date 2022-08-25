import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { delay, map } from 'rxjs/operators';
import { environment } from '../../environments/environment';
import { Router } from '@angular/router';
import { JsonPipe } from '@angular/common';
import { ResponseModel } from '../model/ResponseModel'
import { HttpClient, HttpHeaders, HttpParams, HttpRequest } from '@angular/common/http';
import { DashboardRecentModel } from '../model/DashboardRecentModel';

@Injectable({
  providedIn: 'root'
})
export class DocumentService {

  constructor(private http: HttpClient, private router: Router) {

  }

  getAllMentions(user_id: any): Observable<DashboardRecentModel[]> {
    let apiURL = `${environment.serviceUrl}api_document.php?procedureName=getAllMentions&user_id=` + user_id;
    return this.http.get<DashboardRecentModel[]>(apiURL);

  }
}
