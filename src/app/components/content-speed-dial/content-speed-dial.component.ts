import { Component, Input, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { SpeedDialOptionModel, Users } from 'src/app/model';
import { AppdataService } from 'src/app/service/appdata.service';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentPlanService } from 'src/app/service/content-plan.service';
import { MyworldService } from 'src/app/service/myworld.service';
import { SiteTemplateModel } from 'src/app/usermodels/SiteTemplateModel';

@Component({
  selector: 'app-content-speed-dial',
  templateUrl: './content-speed-dial.component.html',
  styleUrls: ['./content-speed-dial.component.scss']
})
export class ContentSpeedDialComponent implements OnInit {

  @Input() universeId:string='';  
  @Input() contentTypes: string[] = [];
  SpeedDialOptions: SpeedDialOptionModel[] = [];

  constructor(
    private router: Router,
    private contentPlanService: ContentPlanService,
    private authService: AuthenticationService,
    private myworldService: MyworldService,
    private appdataService: AppdataService) {

  }

  ngOnInit(): void {

    this.myworldService.getAllContentTypes().subscribe({
      next: (res) => {
        res.forEach(content => {
          if (this.contentTypes.indexOf(content.name?.toLowerCase()) > -1){

            let sdo: SpeedDialOptionModel = {
              contentType: content.name?.toLowerCase(),
              hyperlink: '/content/' + content.name?.toLowerCase(),
              iconClass: content.fa_icon!.replace("fa-3x", "fa-2x") + " " + " " + content.name.toLowerCase() + "-pri",
              color: content.name.toLowerCase() + "-pri",
              backgroundcolor: content.primary_color
            }
  
            this.SpeedDialOptions.push(sdo);
          }
        });
      }
    });


  }

  createContent(content_type: string) {
    var accountId = (this.authService.getUser() as (Users)).id;
    this.contentPlanService.get_User_Plans().subscribe({
      next: (res: any) => {
        if (res != null) {
          var userContentPlans = res;
          this.contentPlanService.check_create_user_content_plan(userContentPlans, content_type.toLowerCase()).subscribe({
            next: (res) => {
              if (res != null) {
                if (res) {
                  this.myworldService.createContentInUniverseForUser(this.universeId, accountId!, content_type.toLowerCase());
                }
                else {
                  this.router.navigate(["plan/subscription"]);
                }
              }
            }
          });
        }
      }
    });
  }

}
