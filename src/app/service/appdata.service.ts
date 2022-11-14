import { Injectable } from '@angular/core';
import { isDevMode } from '@angular/core';
import { Observable } from 'rxjs';
import { delay, map } from 'rxjs/operators';
import { environment } from '../../environments/environment';
import { Router } from '@angular/router';
import { JsonPipe } from '@angular/common';
import { ResponseModel } from '../model/ResponseModel'
import { HttpClient, HttpHeaders, HttpParams, HttpRequest } from '@angular/common/http';
import { AppConfig, ContentPlans, ContentTypes, Users } from '../model';
import { MyworldService } from './myworld.service';

@Injectable({
  providedIn: 'root'
})
export class AppdataService {

  public ContentPlansList!: ContentPlans[];
  public ContentTypesList!: ContentTypes[];

  constructor(private myworldService: MyworldService, private http: HttpClient, private router: Router) {
    this.myworldService.getAllContentPlans().subscribe(plans => {
      this.ContentPlansList = plans as ContentPlans[]
    });

    this.myworldService.getAllContentTypes().subscribe(contents => {
      this.ContentTypesList = contents as ContentTypes[]
    });
  }

  loginUser(username: string, password: string): Observable<ResponseModel> {
    let apiURL = `${environment.serviceUrl}api_app.php`;

    var user: any = {};
    user.procedureName = "loginUser";
    user.username = username;
    user.password = password;

    if (isDevMode()) {
      apiURL = 'assets/data/user.json';
      return this.http.get<ResponseModel>(apiURL);
    }
    var jsonData = user;

    const httpOptions = {
      headers: new HttpHeaders({
        'Content-Type': 'application/json'
      })
    };

    return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);
  }

  getAllAppConfig(user_id: any): Observable<AppConfig[]> {
    let apiURL = `${environment.serviceUrl}api_myworld.php?procedureName=getAllAppConfig&user_id=` + user_id;
    return this.http.get<AppConfig[]>(apiURL);

  }
  signupUser(user: Users): Observable<ResponseModel> {
    let apiURL = `${environment.serviceUrl}api_app.php`;

    user.procedureName = "signupUser";

    var jsonData = user;

    const httpOptions = {
      headers: new HttpHeaders({
        'Content-Type': 'application/json'
      })
    };

    return this.http.post<ResponseModel>(apiURL, { data: jsonData }, httpOptions);
  }

  getEnvironment(): string {
    if (isDevMode()) {
      return 'Development';
    } else {
      return 'Production';
    }
  }
}