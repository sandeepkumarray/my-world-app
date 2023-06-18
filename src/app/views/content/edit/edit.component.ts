import { Component, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpEventType, HttpResponse } from '@angular/common/http';
import { TabContentComponent } from '@coreui/angular';
import { Users } from 'src/app/model';
import { Content, ContentTemplateModel } from 'src/app/model/ContentTemplateModel';
import { BaseModel } from "src/app/model/BaseModel";
import { ContentBlobObject } from 'src/app/model/ContentBlobObject';
import { AppdataService } from 'src/app/service/appdata.service';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentService } from 'src/app/service/content.service';
import { MyworldService } from 'src/app/service/myworld.service';
import { DocumentService } from 'src/app/service/document.service';
import { constants } from 'src/app/utility/constants';
import { utility } from 'src/app/utility/utility';

@Component({
  selector: 'app-edit',
  templateUrl: './edit.component.html',
  styleUrls: ['./edit.component.scss']
})
export class EditComponent implements OnInit {
  id: any = "";
  content_type: any = "";
  accountId: string = "";
  Constants = constants;
  utility = utility;
  ContentDic: { [key: string]: string } = {};
  headerBackgroundColor: any;
  ContentTemplate: Content = new Content();
  ContentObjectList: ContentBlobObject[] = [];
  remainingSize?: string;
  expandAllToggle: boolean = false;
  allAttachments: ContentBlobObject[] = [];
  focus: boolean = false;
  content_select_value = -1;
  NumberType = Number;
  showURL = true;
  showFile = false;

  constructor(private activatedRoute: ActivatedRoute,
    private appdataService: AppdataService,
    private authService: AuthenticationService,
    private myworldService: MyworldService,
    private contentService: ContentService,
    private documentService: DocumentService,
    private sanitized: DomSanitizer,
    private router: Router) {
    this.id = this.activatedRoute.snapshot.paramMap.get('id');
    this.content_type = this.activatedRoute.snapshot.paramMap.get('content_type');
    this.accountId = (this.authService.getUser() as (Users)).id!;

    this.router.routeReuseStrategy.shouldReuseRoute = () => false;
  }

  ngOnInit(): void {
    
    let allowedTotalContentSize = this.authService.getValue(constants.AllowedTotalContentSize);
    this.remainingSize = utility.SizeSuffix(allowedTotalContentSize);
    this.contentService.getContentDetailsFromTypeID(this.content_type, this.id).subscribe({
      next: response => {
        if (response != null) {
          var jsonString = JSON.stringify(response);
          var jsonObject = JSON.parse(jsonString);
          var dictionary: { [key: string]: string } = {};
          Object.keys(jsonObject).map(function (key) {
            dictionary[(key)] = jsonObject[key];
          });
          this.ContentDic = dictionary;
        }
        else {
          this.router.navigate(["404"]);
        }
      }
    });

    this.myworldService.getContentTypes(utility.titleTransform(this.content_type)).subscribe(res => {
      this.headerBackgroundColor = res.sec_color;
    });

    this.loadContentBlobObjects();

    this.myworldService.getUsersContentTemplate(this.accountId).subscribe(res => {
      let contentTemplateModel = JSON.parse(res.template) as ContentTemplateModel;
      this.ContentTemplate = contentTemplateModel.contents.find(c => c.content_type!.toLowerCase() == this.content_type.toLowerCase())!;

      this.ContentTemplate.categories = this.ContentTemplate.categories.filter(c=> c.is_hidden == false && c.attributes.length > 0)
      .sort((a, b) => a.order - b.order);

      this.ContentTemplate.references = this.ContentTemplate.references.filter(c=> c.is_hidden == false && c.attributes.length > 0)
      .sort((a, b) => a.order - b.order);

      var categories_min_order=  this.ContentTemplate.categories.reduce(function(prev, current) {
        return (prev.order < current.order) ? prev : current
    })

    var references_min_order =  this.ContentTemplate.references.reduce(function(prev, current) {
      return (prev.order < current.order) ? prev : current
  })

      var catIndex = 0;
      this.ContentTemplate.categories.map(c => {
        c.index = catIndex++;
        c.is_active = this.setActive(c.order, categories_min_order.order);
      });
      //catIndex = 0;
      this.ContentTemplate.references.map(c => {
        c.index = catIndex++;
        c.is_active = this.setActive(c.order, references_min_order.order);
      });
    });
  }

  loadContentBlobObjects() {
    this.ContentObjectList = [];
    this.myworldService.getContentBlobObject(this.id, this.content_type.toLowerCase()).subscribe(res => {
      let allowedTotalContentSize = this.authService.getValue(constants.AllowedTotalContentSize);
      let existing_total_size: number = 0;
      if (res != null) {
        res.map(c => {
          const blob = utility.b64toBlob(c.object_blob, c.content_type);
          let url = URL.createObjectURL(blob);
          c.image_url = url;
          c.safeurl = this.sanitized.bypassSecurityTrustResourceUrl('data:image/png;base64,' + c.object_blob);
          c.size = utility.SizeSuffix(c.object_size!);
          existing_total_size = +existing_total_size + +c.object_size!;
          this.ContentObjectList.push(c);
        });

        this.remainingSize = utility.SizeSuffix(allowedTotalContentSize - existing_total_size);
      }
    });

  }

  setActive(order: number, minactive : number): boolean {
    if (order == minactive) {
      return true;
    }
    else
      return false;
  }

  expandAll(tabContent: TabContentComponent) {
    const count = tabContent.panes.filter((obj) => obj.active === true).length;

    if (this.expandAllToggle == false) {
      this.expandAllToggle = true;
      this.ContentTemplate.categories.forEach(category => {
        var elem = document.getElementById(category.label + "_panel");
        var elemTabPane = document.getElementById(category.label + "_tabpane");
        elem?.classList.add("active");
        elemTabPane?.classList.add("active");
        elemTabPane?.classList.add("show");
      });
      // tabContent.panes.forEach(tabPane => {
      //   tabPane.active = true;
      // });
    }
    else {
      this.expandAllToggle = false;
      this.ContentTemplate.categories.forEach(category => {
        var elem = document.getElementById(category.label + "_panel");
        var elemTabPane = document.getElementById(category.label + "_tabpane");
        elem?.classList.remove("active");
        elemTabPane?.classList.remove("active");
        elemTabPane?.classList.remove("show");
      });

      this.ContentTemplate.references.forEach(reference => {
        var elem = document.getElementById(reference.label + "_panel");
        var elemTabPane = document.getElementById(reference.label + "_tabpane");
        elem?.classList.remove("active");
        elemTabPane?.classList.remove("active");
        elemTabPane?.classList.remove("show");
      });

      this.ContentTemplate.categories.forEach(category => {
        var elem = document.getElementById(category.label + "_panel");
        var elemTabPane = document.getElementById(category.label + "_tabpane");
        if (category.index == 0) {
          if (!elem?.classList.contains("active")) {
            elem?.classList.add("active");
            elemTabPane?.classList.add("active");
            elemTabPane?.classList.add("show");
          }
        }
      });

    }
  }

  checkValue($event: any, field_name: string) {

    let value = Number($event.target.checked);

    let model: BaseModel = new BaseModel();
    model._id = this.id;
    model.column_type = field_name;
    model.column_value = value;
    model.content_type = this.content_type.toLowerCase();
    this.contentService.saveData(model).subscribe({
      next: response => {
      }
    });
  }

  onBlur($event: any, field_name: string) {
    this.focus = false;

    let model: BaseModel = new BaseModel();
    model._id = this.id;
    model.column_type = field_name;
    model.column_value = $event.target.value;
    model.content_type = this.content_type.toLowerCase();
    this.contentService.saveData(model).subscribe({
      next: response => {
        if(field_name.toLowerCase() == 'name'){
          this.myworldService.updateContentAttribute(model._id, 'name', model.column_value,model.content_type).subscribe({});
        }
      }
    });
  }

  onQuillBlur($event: any, field_name: string) {
    let model: BaseModel = new BaseModel();
    model._id = this.id;
    model.column_type = field_name;
    model.column_value = $event.editor.root.innerHTML;
    model.content_type = this.content_type.toLowerCase();
    this.contentService.saveData(model).subscribe({
      next: response => {
      }
    });
  }

  addImage(event: any) {
    let accountId = (this.authService.getUser() as (Users)).id;
    this.allAttachments = [];
    let files: FileList = event.target.files;
    for (let i = 0; i < files.length; i++) {
      let aFile: ContentBlobObject = new ContentBlobObject();
      aFile.object_blob = files[i];
      aFile.inProgress = false;
      aFile.progress = 0;
      aFile.object_type = files[i].type;
      aFile.object_name = files[i].name;
      aFile.object_size = files[i].size;
      aFile.content_id = this.id;
      aFile.user_id = Number(accountId!);
      aFile.content_type = this.content_type.toLowerCase();
      this.allAttachments.push(aFile);
    }
  }

  uploadImage() {
    let allowedTotalContentSize = this.authService.getValue(constants.AllowedTotalContentSize);
    this.remainingSize = utility.SizeSuffix(allowedTotalContentSize);
    let existing_total_size: number = 0;
    this.ContentObjectList.forEach(a => existing_total_size += a.object_size!);
    if (this.allAttachments != null) {
      let upload_file_size: number = 0;
      this.allAttachments.forEach(a => upload_file_size += a.object_size!);
      var total_size = upload_file_size + existing_total_size;
      if (total_size <= allowedTotalContentSize) {
        for (let i = 0; i < this.allAttachments.length; i++) {
          this.myworldService.addImageToContent(this.allAttachments[i])
            .subscribe(
              event => {
                if (event.type == HttpEventType.UploadProgress) {
                  const percentDone = Math.round(100 * event.loaded / event.total!);
                  console.log(`File is ${percentDone}% loaded.`);
                } else if (event instanceof HttpResponse) {
                  console.log('File is completely loaded!');
                  //this.loadAttachments();
                  this.loadContentBlobObjects();
                }
              },
              (err) => {
                console.log("Upload Error:", err);
              }, () => {
                console.log("Upload done");
              }
            )
        }
      }
    }
    else {
      //Messagebox
    }
  }

  deleteContentObject(object_id: any) {
    this.myworldService.deleteContentBlobObject(object_id).subscribe(res => {
      window.location.reload();
    });
  }

  getContentSelectValue($event:any, field_name: string){
    let model: BaseModel = new BaseModel();
    model._id = this.id;
    model.column_type = field_name;
    model.column_value = $event;
    model.content_type = this.content_type.toLowerCase();
    this.contentService.saveData(model).subscribe({
      next: response => {
        if(field_name.toLowerCase() == 'universe'){
          this.myworldService.updateContentAttribute(model._id, 'universe_id', model.column_value,model.content_type).subscribe({});
        }
      }
    });
  }

  onImageBlur($event:any){

    let accountId = (this.authService.getUser() as (Users)).id;
    this.allAttachments = [];
    let url : string = $event.target.value;
    let urlName = url;
    if(url.includes('?')){
      urlName = url.split("?")[0];
    }
    urlName = urlName.split('/').pop() as string;

     this.myworldService.getImage(url).subscribe(res => {
      let imageblob = res;
      let aFile: ContentBlobObject = new ContentBlobObject();
      aFile.object_blob = imageblob;
      aFile.inProgress = false;
      aFile.progress = 0;
      aFile.object_type = imageblob.type;
      aFile.object_name = urlName;
      aFile.object_size = imageblob.size;
      aFile.content_id = this.id;
      aFile.user_id = Number(accountId!);
      aFile.content_type = this.content_type.toLowerCase();
      this.allAttachments.push(aFile);
    });
  }

  onImageRadioChange($event:any){
    if($event.target.value == "URL"){
      this.showURL = true;
      this.showFile = false;
    }
    else{
      this.showURL = false;
      this.showFile = true;
    }
  }
}
