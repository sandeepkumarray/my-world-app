import { Injectable } from '@angular/core';
import { Observable, of as ObservableOf } from 'rxjs';
import { ContentPlans, Users } from '../model';
import { AuthenticationService } from './authentication.service';
import { MyworldService } from './myworld.service';
import { from, map, mergeMap } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ContentPlanService {
  userContentPlans: any = new ContentPlans();
  accountId: any;
  constructor(private authService: AuthenticationService,
    private myworldService: MyworldService,) {
    this.load_User_Plans();
  }

  load_User_Plans() {
    this.accountId = (this.authService.getUser() as (Users)).id!;
    this.myworldService.getUserContentPlans(this.accountId).subscribe({
      next: (res) => {
        this.userContentPlans = res;
        console.log("this.userContentPlans", this.userContentPlans);
      }
    });
  }

  get_User_Plans(): Observable<any> {
    this.accountId = (this.authService.getUser() as (Users)).id!;
    return this.myworldService.getUserContentPlans(this.accountId).pipe(
      map((res) => {
        console.log(res);
        return res;
      })
    );
  }

  check_create_content_plan(content_type: string): Observable<any> {
    var return_value = false;

    return this.myworldService.getDashboard(this.accountId).pipe(
      map((res) => {
        console.log(res);
        let dashboardModel = res;
        var content_item_count = dashboardModel[content_type.toLowerCase() + '_total'];
        var planItemCount = this.userContentPlans[content_type.toLowerCase() + '_count'];
        console.log("user content count : " + content_item_count);
        console.log("plan content count : " + planItemCount);

        planItemCount = Number(planItemCount);
        if (content_item_count < planItemCount || this.userContentPlans.name == "Unlimited") {
          console.log("call create proc");
          return true;
        }
        else {
          return false;
        }
      }));
  }
  
  check_content_plan(content_type: string): Observable<any> {
    var return_value = false;

    return this.myworldService.getDashboard(this.accountId).pipe(
      map((res) => {
        console.log(res);
        let dashboardModel = res;
        var content_item_count = dashboardModel[content_type.toLowerCase() + '_total'];
        var planItemCount = this.userContentPlans[content_type.toLowerCase() + '_count'];
        console.log("user content count : " + content_item_count);
        console.log("plan content count : " + planItemCount);

        planItemCount = Number(planItemCount);
        if (this.userContentPlans.name == "Unlimited") {
          return true;
        }
        else {
          if (planItemCount == 0) {
            return false;
          }
          else if (content_item_count <= planItemCount) {
            return true;
          }
          else {
            return false;
          }
        }
      }));
  }
}
