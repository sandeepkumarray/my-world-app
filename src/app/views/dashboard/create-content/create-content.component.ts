import { Component, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { ActivatedRoute, Router } from '@angular/router';
import { ContentPlans, Users } from 'src/app/model';
import { AppdataService } from 'src/app/service/appdata.service';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentService } from 'src/app/service/content.service';
import { MyworldService } from 'src/app/service/myworld.service';

@Component({
  selector: 'app-create-content',
  templateUrl: './create-content.component.html',
  styleUrls: ['./create-content.component.scss']
})
export class CreateContentComponent implements OnInit {
  content_type: string;
  userContentPlans: any;
  dashboardModel: any;

  constructor(private activatedRoute: ActivatedRoute,
    private authService: AuthenticationService,
    private myworldService: MyworldService,
    private router: Router) {
    this.content_type = this.activatedRoute.snapshot.paramMap.get('content_type')!;
  }

  ngOnInit(): void {
    let accountId = (this.authService.getUser() as (Users)).id;

    this.myworldService.getUserContentPlans(accountId).subscribe({
      next: (res) => {
        this.userContentPlans = res;
        let plan_template = JSON.parse(this.userContentPlans.plan_template);
      }
    });

    this.myworldService.getDashboard(accountId).subscribe({
      next: (res) => {
        this.dashboardModel = res;
        var content_item_count = this.dashboardModel[this.content_type.toLowerCase() + '_total'];
        var planItemCount = this.userContentPlans[this.content_type.toLowerCase() + '_count'];

        if (content_item_count < planItemCount || this.userContentPlans.name == "Unlimited") {
          this.myworldService.createItem(this.content_type, accountId).subscribe({
            next: (res) => {
              this.router.navigate([this.content_type.toLowerCase() + "/" + res+ "/edit"]);
            }
          });
          //return RedirectToAction("View" + content_type, content_type, new { id = id });
        }
        else {
          var Message = "You have Exceeded the maximum allowed content for " + this.content_type + ".";
        }
      }
    });
  }

}
