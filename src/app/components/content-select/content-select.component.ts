import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { Users } from 'src/app/model';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentService } from 'src/app/service/content.service';
import { MyworldService } from 'src/app/service/myworld.service';
import { utility } from 'src/app/utility/utility';

@Component({
  selector: 'app-content-select',
  templateUrl: './content-select.component.html',
  styleUrls: ['./content-select.component.scss']
})
export class ContentSelectComponent implements OnInit {
  utility = utility;

  @Input() content_type = '';
  @Input() id_column = 'id';
  @Input() display_column = 'name';
  @Input() select_value = -1;
  @Input() is_Editable = true;
  @Output() selected = new EventEmitter<string>();

  accountId: string = "";
  options = [];


  constructor(private authService: AuthenticationService,
    private myworldService: MyworldService,
    private contentService: ContentService,) {
    this.accountId = (this.authService.getUser() as (Users)).id!;
  }

  ngOnInit(): void {

    this.contentService.getAllContentTypeDataForUser(this.accountId, this.content_type.toLowerCase()).subscribe({
      next: (res) => {
        if (res != null) {
          this.options = res;
        }
      }
    });
  }

  valueChanged($event: any) {
    console.log("$event", $event.target.value);
    let value = $event.target.value;
    this.select_value = Number(value);
    this.selected.emit(value);
  }
}
