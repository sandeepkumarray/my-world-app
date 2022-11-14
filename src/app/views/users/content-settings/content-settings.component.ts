import { Component, OnInit } from '@angular/core';
import { ContentTypes } from 'src/app/model';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentService } from 'src/app/service/content.service';
import { MyworldService } from 'src/app/service/myworld.service';

@Component({
  selector: 'app-content-settings',
  templateUrl: './content-settings.component.html',
  styleUrls: ['./content-settings.component.scss']
})
export class ContentSettingsComponent implements OnInit {
  content_type_list : ContentTypes[]=[];

  constructor(
    private myworldService: MyworldService,
    private contentService: ContentService,
    private authService: AuthenticationService,) { }

  ngOnInit(): void {
    
    this.myworldService.getAllContentTypes().subscribe({
      next: (res) => {
        this.content_type_list = res;
        console.log("content_type_list", res);
        res.map(cnfg => {

        });
      }
    });
  }

}
