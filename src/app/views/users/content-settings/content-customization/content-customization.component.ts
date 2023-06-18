import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Users, BaseModel, ContentTypes, UserContentTemplate } from 'src/app/model';
import { Content, ContentTemplateModel } from 'src/app/model/ContentTemplateModel';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentPlanService } from 'src/app/service/content-plan.service';
import { ContentService } from 'src/app/service/content.service';
import { MyworldService } from 'src/app/service/myworld.service';
import { utility } from 'src/app/utility/utility';

@Component({
  selector: 'app-content-customization',
  templateUrl: './content-customization.component.html',
  styleUrls: ['./content-customization.component.scss']
})
export class ContentCustomizationComponent implements OnInit {
  content_type: any = "";
  accountId: string = "";
  utility = utility;
  userContentPlans: any;
  content_included_plan: boolean = false;
  content_type_details: ContentTypes = new ContentTypes();
  ContentTemplate: Content = new Content();
  UserContentTemplate: UserContentTemplate = new UserContentTemplate();
  contentTemplateModel: ContentTemplateModel = new ContentTemplateModel();
  constructor(private activatedRoute: ActivatedRoute,
    private authService: AuthenticationService,
    private router: Router,
    private myworldService: MyworldService,
    private contentService: ContentService,
    private contentPlanService: ContentPlanService) {
    this.accountId = (this.authService.getUser() as (Users)).id!;
    this.content_type = this.activatedRoute.snapshot.paramMap.get('content_type');
  }

  ngOnInit(): void {
    this.content_type_details = this.authService.getValue(utility.titleTransform(this.content_type)) as ContentTypes;
    this.content_type_details.fa_icon = this.content_type_details.fa_icon! + " " + this.content_type_details.name.toLowerCase() + "-pri";
    this.content_type_details.name_singular = this.content_type_details.name.toUpperCase().slice(0, -1);

    this.myworldService.getUsersContentTemplate(this.accountId).subscribe(res => {
      this.UserContentTemplate = res as UserContentTemplate;
      this.contentTemplateModel = JSON.parse(res.template) as ContentTemplateModel;
      this.ContentTemplate = this.contentTemplateModel.contents.find(c => c.content_type!.toLowerCase() == this.content_type.toLowerCase())!;

      this.ContentTemplate.categories.map(c => {
      });

      this.ContentTemplate.categories = this.ContentTemplate.categories.sort((a, b) => a.order - b.order);
      this.ContentTemplate.references = this.ContentTemplate.references.sort((a, b) => a.order - b.order);

    });
  }
  callUpdateUserContentTemplate(category: any){

    var category_index = this.ContentTemplate.categories.findIndex(c => c.id == category.id);
    this.ContentTemplate.categories[category_index] = category;

    var content_index = this.contentTemplateModel.contents.findIndex(c => c.content_type!.toLowerCase() == this.content_type.toLowerCase());
    this.contentTemplateModel.contents[content_index] = this.ContentTemplate;
    
    this.UserContentTemplate.template = JSON.stringify(this.contentTemplateModel);

    this.myworldService.updateUserContentTemplate(this.UserContentTemplate).subscribe(res => {

    });
  }

  renameCategory(category: any) {
    this.callUpdateUserContentTemplate(category);
  }

  hideCategory(category: any) {
    category.is_hidden = true;
    this.callUpdateUserContentTemplate(category);
  }

  showCategory(category: any) {
    category.is_hidden = false;
    this.callUpdateUserContentTemplate(category);
  }
}
