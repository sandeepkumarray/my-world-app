import { Component, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { ActivatedRoute, Router } from '@angular/router';
import { Users } from 'src/app/model';
import { Timelines } from 'src/app/model/Timelines';
import { ContentBlobObject } from 'src/app/model/ContentBlobObject';
import { Content, ContentTemplateModel } from 'src/app/model/ContentTemplateModel';
import { AppdataService } from 'src/app/service/appdata.service';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentService } from 'src/app/service/content.service';
import { MyworldService } from 'src/app/service/myworld.service';
import { constants } from 'src/app/utility/constants';
import { utility } from 'src/app/utility/utility';

@Component({
  selector: 'app-view-timelines',
  templateUrl: './view-timelines.component.html',
  styleUrls: ['./view-timelines.component.scss']
})
export class ViewTimelinesComponent implements OnInit {
  id: any = "";
  contentAvailable: boolean = false;
  timelineDic: { [key: string]: string } = {};
  headerBackgroundColor: any;
  ContentTemplate: Content = new Content();
  ContentObjectList: ContentBlobObject[] = [];
  Constants = constants;

  constructor(private activatedRoute: ActivatedRoute,
    private appdataService: AppdataService,
    private authService: AuthenticationService,
    private myworldService: MyworldService,
    private contentService: ContentService,
    private sanitized: DomSanitizer,
    private router: Router) {
    this.id = this.activatedRoute.snapshot.paramMap.get('id');
  }

  ngOnInit(): void {
    let accountId = (this.authService.getUser() as (Users)).id;
    this.contentService.getTimelines(accountId, this.id).subscribe({
      next: response => {
        if (response != null) {
          var jsonString = JSON.stringify(response);
          var jsonObject = JSON.parse(jsonString);
          var dictionary: { [key: string]: string } = {};
          Object.keys(jsonObject).map(function (key) {
            dictionary[(key)] = jsonObject[key];
          });
          this.timelineDic = dictionary;
          console.log(dictionary);
          this.contentAvailable = true;
        }
        else {
          this.router.navigate(["404"]);
        }
      }
    });

    this.myworldService.getContentTypes('Timelines').subscribe(res => {
      this.headerBackgroundColor = res.sec_color;
    });

    this.myworldService.getContentBlobObject(this.id, 'timelines').subscribe(res => {
      if(res != null){
        res.map(c => {
          const blob = utility.b64toBlob(c.object_blob, c.content_type);
          let url = window.URL.createObjectURL(blob);              
          c.image_url = url;
          this.ContentObjectList.push(c);
        });
      }
    });

    this.myworldService.getUsersContentTemplate(accountId).subscribe(res => {
      let contentTemplateModel = JSON.parse(res.template) as ContentTemplateModel;
      this.ContentTemplate = contentTemplateModel.contents.find(c => c.content_type == "timelines")!;
      console.log(this.ContentTemplate);
      this.ContentTemplate.categories = this.ContentTemplate.categories.sort((a, b) => a.order - b.order);
      this.ContentTemplate.references = this.ContentTemplate.references.sort((a, b) => a.order - b.order);
    });
  }

  senitizeHtmlContent(value: any) {
    const html = this.sanitized.bypassSecurityTrustHtml(value);
    return html;
  }
}
