import { ChangeDetectorRef, Component, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { ActivatedRoute, Router } from '@angular/router';
import { ContentPlans, ContentTypes, Users } from 'src/app/model';
import { DashboardItem } from 'src/app/model/DashboardItem';
import { DashboardRecentModel } from 'src/app/model/DashboardRecentModel';
import { PlanTemplateModel } from 'src/app/model/PlanTemplateModel';
import { AppdataService } from 'src/app/service/appdata.service';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentPlanService } from 'src/app/service/content-plan.service';
import { ContentService } from 'src/app/service/content.service';
import { MyworldService } from 'src/app/service/myworld.service';
import { utility } from 'src/app/utility/utility';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit {

  userContentPlans: any;
  dashboardModel: any;
  DashboardItems: DashboardItem[] = [];
  DashboardRecentList: DashboardRecentModel[] = [];
  DashboardCreateItemList: DashboardItem[] = [];
  PlanTemplate: PlanTemplateModel = new PlanTemplateModel();
  content_type_list!: ContentTypes[];

  constructor(private activatedRoute: ActivatedRoute,
    private appdataService: AppdataService,
    private authService: AuthenticationService,
    private myworldService: MyworldService,
    private contentService: ContentService,
    private sanitized: DomSanitizer,
    private router: Router,
    private contentPlanService: ContentPlanService,
    private changeDetection: ChangeDetectorRef) {
  }

  ngOnInit(): void {
    let accountId = (this.authService.getUser() as (Users)).id;
    console.log("accountId", accountId);
    this.myworldService.getAllContentTypes().subscribe({
      next: (res) => {
        this.content_type_list = res;
        console.log("content_type_list", res);
        res.map(cnfg => {
          this.authService.setValue(cnfg.name, cnfg);
        });
      }
    });

    this.myworldService.getDashboard(accountId).subscribe({
      next: (res) => {
        if (res != null) {
          this.dashboardModel = res;
          var createList = utility.getRandom(this.content_type_list, 3);
          createList.forEach(c => {
            let dashboardItem: DashboardItem = new DashboardItem();
            dashboardItem.Header = c.name,
              dashboardItem.icon = c.icon,
              dashboardItem.primary_color = c.primary_color,
              dashboardItem.CountString = "You've created " + this.dashboardModel[c.name!.toLowerCase() + '_total'] + " " + c.name,
              dashboardItem.ItemKey = c.name,
              dashboardItem.Controller = c.name,
              dashboardItem.Action = "View " + c.name
            this.DashboardCreateItemList.push(dashboardItem);
            this.changeDetection.detectChanges();
          });
        }
      }
    });

    this.myworldService.getRecents(accountId).subscribe({
      next: (res) => {
        if (res != null) {
          console.log(res);
          this.DashboardRecentList = res.filter(r => r.updated_at != null).sort(
            (objA, objB) => new Date(objB.updated_at!).getTime()! - new Date(objA.updated_at!).getTime()!).slice(0, 5);
          this.DashboardRecentList.forEach(r => {
            r.timeSince = utility.timeSince(new Date(r.updated_at!));
            r.url = '#/content/' + r.content_type + '/' + r.id;
            if (r.content_type == 'documents') {
              r.url = '#/documents/' + r.id;
            }
          });
          this.changeDetection.detectChanges();
        }
      }
    });

    this.myworldService.getUserContentPlans(accountId).subscribe({
      next: (res) => {
        if (res != null) {
          this.userContentPlans = res;
          this.PlanTemplate = JSON.parse(this.userContentPlans.plan_template);
          this.PlanTemplate.PlanContentList!.forEach((item) => {
            let dashboardItem: DashboardItem = new DashboardItem();
            dashboardItem.Header = item.name;
            dashboardItem.CountString = this.dashboardModel[item.name!.toLowerCase() + '_total'];
            dashboardItem.Color = this.content_type_list!.find(c => c.name!.toLowerCase() == item.name!.toLowerCase())?.primary_color;
            dashboardItem.icon = this.content_type_list!.find(c => c.name!.toLowerCase() == item.name!.toLowerCase())?.icon;
            dashboardItem.ItemKey = item.name;
            dashboardItem.Controller = item.name!.toLowerCase();
            dashboardItem.Action = "View " + item.name;
            this.DashboardItems.push(dashboardItem);
            this.changeDetection.detectChanges();
          });
        }
      }
    });
  }

  createContent(content_type: string) {
    var accountId = (this.authService.getUser() as (Users)).id;
    this.contentPlanService.check_create_content_plan(content_type.toLowerCase()).subscribe({
      next: (res) => {
        if (res != null) {
          if (res) {
            this.myworldService.createContentForUser(accountId!, content_type.toLowerCase());
          }
          else {
            this.router.navigate(["plan/subscription"]);
          }
        }
      }
    });
  }

}
