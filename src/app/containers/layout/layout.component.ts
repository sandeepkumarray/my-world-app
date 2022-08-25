import { Component, OnInit } from '@angular/core';
import { MyworldService } from '../../service/myworld.service'
import { navItems } from './_nav';
import { INavData } from '@coreui/angular';

import { DomSanitizer, SafeHtml } from "@angular/platform-browser";
import { ContentPlans, ContentTypes } from 'src/app/model';
import { SiteTemplateModel } from 'src/app/usermodels/SiteTemplateModel';
import { AppdataService } from 'src/app/service/appdata.service';

@Component({
  selector: 'app-layout',
  templateUrl: './layout.component.html',
  styleUrls: ['./layout.component.css']
})
export class LayoutComponent implements OnInit {

  FreePlanContents: string[] = [];
  ContentTypesList: ContentTypes[] = [];
  public navItems = navItems;
  public navItemsContents: any[];
  fixedNavItems: any[] = [];
  planNavItems: any[] = [];

  public perfectScrollbarConfig = {
    suppressScrollX: true,
  };

  constructor(private myworldService: MyworldService, private appdataService: AppdataService, private sanitizer: DomSanitizer) {
    this.navItemsContents = [];
    this.createNavMenu();
  }

  ngOnInit(): void {


  }

  createNavMenu() {
    let dashboardNavdata: any = {
      name: 'Dashboard',
      url: '/dashboard',
      iconComponent: { name: 'cil-speedometer' },
      badge: {
        color: 'info',
        text: 'NEW'
      }
    };
    this.fixedNavItems.push(dashboardNavdata);

    let componentsNavdata: any = {
      name: 'Components',
      title: true
    };
    this.planNavItems.push(componentsNavdata);

    let moreNavdata: any = {
      name: 'more',
      title: false,
      children: []
    };
    this.appdataService.getAllAppConfig(1).subscribe(contents => {
      console.log(contents);
    });
    console.log(this.appdataService.ContentPlansList);
    this.myworldService.getContentPlans(1, 1).subscribe({
      next: (plan) => {
        this.FreePlanContents = plan.contents.split(',');
        let freePlanTemplate: SiteTemplateModel = JSON.parse(plan.plan_template);
        freePlanTemplate.PlanContentList.forEach(planContent => {
          var content:any = this.appdataService.ContentTypesList.find(c => c.name == planContent.name);

          let navdata: any = {
            name: content.name,
            url: '/' + content.name?.toLowerCase(),
            icon: content.fa_icon + " " + content.name + "-pri", //this.sanitizer.bypassSecurityTrustHtml(content.icon),                  
            badge: {
              color: 'info',
              text: 'NEW'
            }
          };
          this.fixedNavItems.push(navdata);
        });
        
        

        this.myworldService.getAllContentTypes().subscribe({
          next: (v) => {
            v.map(content => {
              if (this.FreePlanContents.indexOf(content.name!) > -1) {

              }
              else {
                let navdata: any = {
                  name: content.name,
                  url: '/' + content.name?.toLowerCase(),
                  icon: content.fa_icon + " " + content.name + "-pri", //this.sanitizer.bypassSecurityTrustHtml(content.icon),
                  badge: {
                    color: 'info',
                    text: 'NEW'
                  }
                };
                moreNavdata.children.push(navdata);
              }
            });
            this.planNavItems = this.planNavItems.concat(moreNavdata);
            this.fixedNavItems = this.fixedNavItems.concat(this.planNavItems);
            this.navItemsContents = this.fixedNavItems;
          },
          error: (e) => console.error(e),
          complete: () => console.info('complete')
        });
      }
    });

    console.log(this.navItemsContents);
  }
}
