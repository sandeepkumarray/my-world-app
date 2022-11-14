import { Component, OnInit } from '@angular/core';
import { ContentPlans, ContentTypes, UserPlan, Users } from 'src/app/model';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentPlanService } from 'src/app/service/content-plan.service';
import { MyworldService } from 'src/app/service/myworld.service';

@Component({
  selector: 'app-plan',
  templateUrl: './plan.component.html',
  styleUrls: ['./plan.component.scss']
})
export class PlanComponent implements OnInit {

  contentPlans: ContentPlans[] = [];
  current_plan: ContentPlans = new ContentPlans();
  constructor( private authService: AuthenticationService,
    private myworldService: MyworldService,
    private contentPlanService: ContentPlanService,) { }

  ngOnInit(): void {
    this.contentPlanService.get_User_Plans().subscribe({
      next: (res: any) => {
        if (res != null) {
          this.current_plan = res;

          this.myworldService.getAllContentPlans().subscribe({
            next: response => {
              //this.contentPlans = response;
              response.map(
                (c) => {
                  if (this.current_plan.id <= c.id) {

                    c.plan_contents = [];
                    var plan_desc = c.plan_description.split(',');
                    if (this.current_plan.id == c.id) {
                      c.is_currentPlan = true;
                    } else {
                      c.is_currentPlan = false;
                    }
                    plan_desc.forEach(d => {
                      var content_desc = d.split(' ');
                      var content_type = content_desc[1];
                      var count = content_desc[0];
                      var content_type_details = this.authService.getValue(content_type) as ContentTypes;
                      content_type_details.count = Number(count);
                      content_type_details.fa_icon = content_type_details.fa_icon!.replace("fa-3x", "") + " " + content_type_details.name.toLowerCase() + "-pri";

                      c.plan_contents.push(content_type_details);
                    });
                    this.contentPlans.push(c);
                  }
                }
              );
            }
          });
        }
      }
    });
  }

  changePlan(plan_id: any) {
    let accountId = (this.authService.getUser() as (Users)).id;
    let userplan: UserPlan = new UserPlan();
    userplan.user_id = accountId;
    userplan.plan_id = plan_id;
    console.log("userplan",userplan);
    this.myworldService.updateUserPlan(userplan).subscribe({
      next: response => {
        window.location.reload();
      }
    });
  }
}
