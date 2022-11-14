import { Component, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { ActivatedRoute, Router } from '@angular/router';
import { Users } from 'src/app/model';
import { ContentBlobObject } from 'src/app/model/ContentBlobObject';
import { Content, ContentTemplateModel } from 'src/app/model/ContentTemplateModel';
import { AppdataService } from 'src/app/service/appdata.service';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentService } from 'src/app/service/content.service';
import { MyworldService } from 'src/app/service/myworld.service';
import { constants } from 'src/app/utility/constants';
import { utility } from 'src/app/utility/utility';

@Component({
  selector: 'app-view',
  templateUrl: './view.component.html',
  styleUrls: ['./view.component.scss']
})
export class ViewComponent implements OnInit {
  id: any = "";
  content_type: any = "";
  accountId: string = "";
  contentAvailable: boolean = false;
  ContentDic: { [key: string]: string } = {};
  headerBackgroundColor: any;
  ContentTemplate: Content = new Content();
  ContentObjectList: ContentBlobObject[] = [];
  Constants = constants;
  utility = utility;
  NumberType = Number;

  constructor(private activatedRoute: ActivatedRoute,
    private appdataService: AppdataService,
    private authService: AuthenticationService,
    private myworldService: MyworldService,
    private contentService: ContentService,
    private sanitized: DomSanitizer,
    private router: Router) {
    this.id = this.activatedRoute.snapshot.paramMap.get('id');
    this.content_type = this.activatedRoute.snapshot.paramMap.get('content_type');
    this.accountId = (this.authService.getUser() as (Users)).id!;

    this.router.routeReuseStrategy.shouldReuseRoute = () => false;
  }

  ngOnInit(): void {
    this.contentService.getContentDetailsFromTypeID(this.content_type, this.id).subscribe({
      next: (response) => {
        if (response != null) {
          var jsonString = JSON.stringify(response);
          var jsonObject = JSON.parse(jsonString);
          var dictionary: { [key: string]: string } = {};
          Object.keys(jsonObject).map(function (key) {
            dictionary[(key)] = jsonObject[key];
          });
          this.ContentDic = dictionary;
          console.log("dictionary", dictionary);
          this.contentAvailable = true;
        }
        else {
          this.router.navigate(["404"]);
        }
      }
    });


    this.myworldService.getContentTypes(utility.titleTransform(this.content_type)).subscribe(res => {
      this.headerBackgroundColor = res.sec_color;
    });

    this.myworldService.getContentBlobObject(this.id, this.content_type.toLowerCase()).subscribe(res => {
      if (res != null) {
        res.map(c => {
          const blob = utility.b64toBlob(c.object_blob, c.content_type);
          let url = window.URL.createObjectURL(blob);
          c.image_url = url;
          this.ContentObjectList.push(c);
        });
      }
    });

    this.myworldService.getUsersContentTemplate(this.accountId).subscribe(res => {
      let contentTemplateModel = JSON.parse(res.template) as ContentTemplateModel;
      this.ContentTemplate = contentTemplateModel.contents.find(c => c.content_type!.toLowerCase() == this.content_type.toLowerCase())!;
      console.log(this.ContentTemplate);
      this.ContentTemplate.categories.map(c => {
        let attributes_with_values_count = 0;
        c.attributes.map(a => {
          a.field_value = this.ContentDic[a.field_name];
          if (this.ContentDic[a.field_name] != '') {
            attributes_with_values_count++;
          }
        });
        if (attributes_with_values_count > 0) {
          c.is_active = true;
        }
        else{
          c.is_active = false;
        }
      });
      
      this.ContentTemplate.categories = this.ContentTemplate.categories.sort((a, b) => a.order - b.order);
      this.ContentTemplate.references = this.ContentTemplate.references.sort((a, b) => a.order - b.order);

    });
  }

  senitizeHtmlContent(value: any) {
    const html = this.sanitized.bypassSecurityTrustHtml(value);
    return html;
  }
}
