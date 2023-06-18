import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Users, BaseModel, ContentTypes } from 'src/app/model';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentPlanService } from 'src/app/service/content-plan.service';
import { ContentService } from 'src/app/service/content.service';
import { MyworldService } from 'src/app/service/myworld.service';
import { utility } from 'src/app/utility/utility';

@Component({
  selector: 'app-content',
  templateUrl: './content.component.html',
  styleUrls: ['./content.component.scss']
})
export class ContentComponent implements OnInit {
  content_type: any = "";
  accountId: string = "";
  contentList: any[] = [];
  public currentDeleteId: any;
  utility = utility;
  userContentPlans: any;
  content_included_plan: boolean = false;
  content_type_details: ContentTypes = new ContentTypes();

  constructor(private activatedRoute: ActivatedRoute,
    private authService: AuthenticationService,
    private router: Router,
    private myworldService: MyworldService,
    private contentService: ContentService,
    private contentPlanService: ContentPlanService) {

    this.content_type = this.activatedRoute.snapshot.paramMap.get('content_type');
    this.accountId = (this.authService.getUser() as (Users)).id!;

    this.router.routeReuseStrategy.shouldReuseRoute = () => false;
  }

  ngOnInit(): void {
    this.content_type_details = this.authService.getValue(utility.titleTransform(this.content_type)) as ContentTypes;
    this.content_type_details.fa_icon = this.content_type_details.fa_icon! + " " + this.content_type_details.name.toLowerCase() + "-pri";
    this.content_type_details.name_singular = this.content_type_details.name.toUpperCase().slice(0, -1);

    this.contentPlanService.check_content_plan(this.content_type.toLowerCase()).subscribe({
      next: (res) => {
        if (res != null) {
          this.content_included_plan = res;
          if (this.content_included_plan) {
            this.contentService.getAllContentTypeDataForUser(this.accountId, this.content_type.toLowerCase()).subscribe({
              next: (res) => {
                if (res != null) {
                  res.map((b: any) => {
                    this.myworldService.getContentBlobObject(b.id, this.content_type.toLowerCase()).subscribe(conObjectResponse => {
                      if (conObjectResponse) {
                        let firstObject = conObjectResponse[0];
                        const blob = utility.b64toBlob(firstObject.object_blob, firstObject.content_type);
                        let url = window.URL.createObjectURL(blob);
                        b.image_url = url;
                      }
                      else {
                        b.image_url = "assets/img/cards/" + utility.titleTransform(this.content_type) + ".png";
                      }

                      b.content_name = b.Name;
                      if (this.content_type.toLowerCase() == 'universes') {
                        b.content_name = b.name;
                      }

                      this.contentList.push(b);
                      this.contentList = this.contentList.sort((a, b) => a.id! - b.id!);
                    });
                  });
                }
              }
            });
          }
        }
      }
    });


  }

  createContent() {
    this.contentPlanService.check_create_content_plan(this.content_type.toLowerCase()).subscribe({
      next: (res) => {
        if (res != null) {
          if (res) {
            this.myworldService.createContentForUser(this.accountId, this.content_type.toLowerCase());
          }
          else {
            this.router.navigate(["plan/subscription"]);
          }
        }
      }
    });

  }

  onDelete(id: any): void {
    this.currentDeleteId = id;
  }

  deleteContent(option: string) {
    if (option == "YES") {
      this.myworldService.deleteContent(this.currentDeleteId, this.content_type.toLowerCase()).subscribe({
        next: (res) => {
          window.location.reload();
        }
      });
    }
    this.currentDeleteId = 0;
  }
}
