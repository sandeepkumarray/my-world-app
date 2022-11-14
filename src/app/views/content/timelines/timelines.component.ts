import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Users } from 'src/app/model';
import { Timelines } from 'src/app/model/Timelines';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentService } from 'src/app/service/content.service';
import { MyworldService } from 'src/app/service/myworld.service';
import { constants } from 'src/app/utility/constants';
import { utility } from 'src/app/utility/utility';

@Component({
  selector: 'app-timelines',
  templateUrl: './timelines.component.html',
  styleUrls: ['./timelines.component.css']
})
export class TimelinesComponent implements OnInit {

  public timelines: Timelines[] = [];
  public currentDeleteId: any;
    constructor(private authService: AuthenticationService, private router: Router, private myworldService: MyworldService,
    private contentService: ContentService) {

  }
  ngOnInit(): void {
    this.index();
  }

  index(): void {
    this.timelines = [];
    let accountId = (this.authService.getUser() as (Users)).id;
    console.log(accountId);
    let imageFormat = this.authService.getValue(constants.ContentImageUrlFormat);
    console.log(imageFormat);
    this.contentService.getAllTimelines(accountId).subscribe({
      next: (res) => {
        res.map(b => {
          this.myworldService.getContentBlobObject(b.id, 'timelines').subscribe(conObjectResponse => {
            if (conObjectResponse) {
              let firstObject = conObjectResponse[0];
              const blob = utility.b64toBlob(firstObject.object_blob, firstObject.content_type);
              let url = window.URL.createObjectURL(blob);              
              b.image_url = url;
            }
            else {
               b.image_url = "assets/img/cards/Timelines.png";
              console.log(b);
            }
            this.timelines.push(b);
            this.timelines = this.timelines.sort((a, b) => a.id! - b.id!);
          });
        });
      }
    });
  }

    onDelete(id: any): void {
        console.log("on delete for id " + id);
        this.currentDeleteId = id;
    }

    deleteTimeline(option : string){
        if (option == "YES") {
            let timeline = new Timelines();
            timeline.id = this.currentDeleteId;
            this.contentService.deleteTimeline(timeline).subscribe({
                next: response => {
                    console.log(response);
                    this.index();
                }
            });
        }
        this.currentDeleteId = 0;
    }
}
