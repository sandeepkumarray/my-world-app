import { Component, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { Router } from '@angular/router';
import { Users } from 'src/app/model';
import { ContentBlobObject } from 'src/app/model/ContentBlobObject';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { MyworldService } from 'src/app/service/myworld.service';
import { utility } from 'src/app/utility/utility';
import * as JSZip from 'jszip';
import { FileSaverService } from 'ngx-filesaver';

@Component({
  selector: 'app-images-manage',
  templateUrl: './images-manage.component.html',
  styleUrls: ['./images-manage.component.scss']
})
export class ImagesManageComponent implements OnInit {

  ContentObjectList: any[] = [];
  constructor(private sanitizer: DomSanitizer,
    private authService: AuthenticationService,
    private myworldService: MyworldService,
    private router: Router,
    private fileSaverService: FileSaverService) { }

  ngOnInit(): void {
    let accountId = (this.authService.getUser() as (Users)).id;
    this.ContentObjectList = [];
    this.myworldService.getAllContentBlobObject(accountId).subscribe(res => {
      if (res != null) {
        res.map(b => {
          let item: any = {};
          item.object_id = b.object_id;
          item.object_name = b.object_name;
          item.object_type = b.object_type;
          item.object_size = b.object_size;
          item.object_blob = b.object_blob;
          item.content_type = b.content_type;
          item.content_id = b.content_id;
          item.created_at = b.created_at;
          item.icon = b.icon;
          item.primary_color = b.primary_color;
          item.fa_icon = b.fa_icon!.replace("fa-3x", "") + " " + b.content_type.toLowerCase() + "-pri";
          item.timeSince = utility.timeSince(new Date(b.created_at!));
          item.name = b.name;

          item.image_url = this.sanitizer.bypassSecurityTrustResourceUrl('data:' + b.object_type + ';base64,' + b.object_blob);
          this.ContentObjectList.push(item);
        });
      }
    });
  }

  back() {
    window.history.back();
  }

  deleteImage(object_id: any) {
    this.myworldService.deleteContentBlobObject(object_id).subscribe({
      next: (res) => {
        window.location.reload();
      }
    });
  }


  downloadAllImages() {
    var zip = new JSZip();

    this.ContentObjectList.forEach((_value: any, i: any) => {
      zip!.file(_value.content_type + "_" + _value.content_id +" _"  + _value.name + "_" + _value.object_id + "_" + _value.object_name, _value.object_blob, { base64: true });
    });

    zip.generateAsync({ type: "blob" }).then((content) => {
      this.fileSaverService.save(content, "MyWorldImages.zip");
    });
  }
}
