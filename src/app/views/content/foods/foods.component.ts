import { Component, OnInit } from '@angular/core';
import { Users } from 'src/app/model';
import { Foods } from 'src/app/model/Foods';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentService } from 'src/app/service/content.service';
import { MyworldService } from 'src/app/service/myworld.service';
import { constants } from 'src/app/utility/constants';
import { utility } from 'src/app/utility/utility';

@Component({
  selector: 'app-foods',
  templateUrl: './foods.component.html',
  styleUrls: ['./foods.component.css']
})
export class FoodsComponent implements OnInit {

  public foods: Foods[] = [];
  constructor(private authService: AuthenticationService, private myworldService: MyworldService,
    private contentService: ContentService) {

  }
  ngOnInit(): void {
    this.index();
  }

  index(): void {
    let accountId = (this.authService.getUser() as (Users)).id;
    console.log(accountId);
    let imageFormat = this.authService.getValue(constants.ContentImageUrlFormat);
    console.log(imageFormat);
    this.contentService.getAllFoods(accountId).subscribe({
      next: (res) => {
        res.map(b => {
          this.myworldService.getContentBlobObject(b.id, 'foods').subscribe(conObjectResponse => {
            if (conObjectResponse) {
              let firstObject = conObjectResponse[0];
              const blob = utility.b64toBlob(firstObject.object_blob, firstObject.content_type);
              let url = window.URL.createObjectURL(blob);              
              b.image_url = url;
            }
            else {
               b.image_url = "assets/img/cards/Foods.png";
              console.log(b);
            }
            this.foods.push(b);
            this.foods = this.foods.sort((a, b) => a.id! - b.id!);
          });
        });
      }
    });
  }

  onDelete(id: any): void {
    console.log("on delete for id " + id);
  }
}
