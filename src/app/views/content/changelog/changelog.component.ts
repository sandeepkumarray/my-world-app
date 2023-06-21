import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { ContentTypes } from 'src/app/model';
import { ContentChangeEvents } from 'src/app/model/ContentChangeEvents';
import { AppdataService } from 'src/app/service/appdata.service';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentService } from 'src/app/service/content.service';
import { MyworldService } from 'src/app/service/myworld.service';
import { utility } from 'src/app/utility/utility';

@Component({
  selector: 'app-changelog',
  templateUrl: './changelog.component.html',
  styleUrls: ['./changelog.component.scss']
})
export class ChangelogComponent implements OnInit {

  id: any = "";
  content_type: any = "";
  content_name: any ="";
  contentTypeData: ContentTypes = new ContentTypes;
  changes: ContentChangeEvents[] = [];

  constructor(private activatedRoute: ActivatedRoute,
    private myworldService: MyworldService,
    private contentService: ContentService,
    private authService: AuthenticationService,
    private appdataService: AppdataService) {
    this.id = this.activatedRoute.snapshot.paramMap.get('id');
    this.content_type = this.activatedRoute.snapshot.paramMap.get('content_type');
  }

  ngOnInit(): void {

    this.contentTypeData = this.authService.getValue(utility.titleTransform(this.content_type)) as ContentTypes;
    this.contentTypeData.icon = this.contentTypeData.icon!.replace("fa-3x", "") + " ";
    this.myworldService.getContentAttributes(this.content_type, this.id).subscribe({
      next: response => {
        console.log('response', response)
        if (response != null) {
          this.content_name = response.name;
        }
      }
    });

    this.contentService.getChangelogforContent(this.content_type, this.id).subscribe({
      next: response => {
        console.log('response', response);
        console.log(' this.contentTypeData ',  this.contentTypeData );
       
        if (response != null) {
          this.changes = response;
          this.changes.forEach(r => {
            r.timeSince = utility.timeSince(new Date(r.created_at!));
            
          });
          console.log('this.changes', this.changes)
        }
      }
    });
  }

}
