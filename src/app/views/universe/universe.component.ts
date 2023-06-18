import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { AppdataService } from 'src/app/service/appdata.service';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentTypes, Documents, Users } from 'src/app/model';
import { DashboardItem } from 'src/app/model/DashboardItem';
import { ContentService } from 'src/app/service/content.service';
import { MyworldService } from 'src/app/service/myworld.service';
import { utility } from 'src/app/utility/utility';
import { ContentPlanService } from 'src/app/service/content-plan.service';
import { DocumentService } from 'src/app/service/document.service';

@Component({
  selector: 'app-universe',
  templateUrl: './universe.component.html',
  styleUrls: ['./universe.component.scss']
})
export class UniverseComponent implements OnInit {

  id: any = "";
  accountId: string = "";
  UniverseContents: any[] = [];
  universe_image_url: any;
  content_type: string = "universes";
  name: string = "";
  content_type_list: ContentTypes[] = [];
  ContentDic: { [key: string]: any[] } = {};

  constructor(private activatedRoute: ActivatedRoute,
    private appdataService: AppdataService,
    private contentService: ContentService,
    private documentService: DocumentService,
    private myworldService: MyworldService,
    private authService: AuthenticationService,
    private router: Router,
    private contentPlanService: ContentPlanService) {
    this.id = this.activatedRoute.snapshot.paramMap.get('id');
    this.accountId = (this.authService.getUser() as (Users)).id!;
  }

  ngOnInit(): void {

    this.myworldService.getAllContentTypes().subscribe({
      next: (res) => {
        this.content_type_list = res;
        res.map(cnfg => {
          this.authService.setValue(cnfg.name, cnfg);
        });
      }
    });

    this.contentService.getUniverses(this.accountId, this.id).subscribe({
      next: (res) => {
        if (res != null) {
          this.name = res.name;
        }
      }
    });

    this.myworldService.getContentBlobObject(this.id, this.content_type.toLowerCase()).subscribe(conObjectResponse => {
      if (conObjectResponse) {
        let firstObject = conObjectResponse[0];
        const blob = utility.b64toBlob(firstObject.object_blob, firstObject.content_type);
        let url = window.URL.createObjectURL(blob);
        this.universe_image_url = url;
      }
      else {
        this.universe_image_url = "assets/img/cards/" + utility.titleTransform(this.content_type) + ".png";
      }
    });

    this.myworldService.getContentsForUniverse(this.accountId, this.id).subscribe(res => {
      if (res != null) {
        res.forEach(r => {
          r.timeSince = utility.timeSince(new Date(r.updated_at!));
          r.url = '#/content/' + r.content_type + '/' + r.id;
          if (r.content_type == 'documents') {
            r.url = '#/documents/' + r.id;
          }
          let grouped = utility.groupByKey(res, 'content_type');

          var jsonString = JSON.stringify(grouped);
          var jsonObject = JSON.parse(jsonString);
          var dictionary: { [key: string]: any[] } = {};
          Object.keys(jsonObject).map(function (key) {
            dictionary[(key)] = jsonObject[key];
          });
          this.ContentDic = dictionary;
          console.log("ContentDic", this.ContentDic)
        });

      }
    });
  }

  createContent(content_type: string) {
    this.contentPlanService.get_User_Plans().subscribe({
      next: (res: any) => {
        if (res != null) {
          var userContentPlans = res;
          this.contentPlanService.check_create_user_content_plan(userContentPlans, content_type.toLowerCase()).subscribe({
            next: (res) => {
              if (res != null) {
                if (res) {
                  this.myworldService.createContentInUniverseForUser(this.id, this.accountId, content_type.toLowerCase());
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

  createDocument() {
    let accountId = (this.authService.getUser() as (Users)).id;
    let document: Documents = new Documents();
    document.body = "";
    document.title = "New Document";
    document.user_id = accountId;
    document.folder_id = 0;
    document.universe_id = this.id;
    
    this.documentService.addDocuments(document).subscribe({
      next: (res) => {
        this.router.navigate(["documents/" + res + "/edit"]);
      }
    });
  }


}
