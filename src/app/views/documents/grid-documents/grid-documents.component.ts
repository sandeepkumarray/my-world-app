import { Component, OnInit, Input } from '@angular/core';
import { Router } from '@angular/router';
import { Documents, Folders, Users } from 'src/app/model';
import { BaseModel } from 'src/app/model/BaseModel';
import { Universes } from 'src/app/model/Universes';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentService } from 'src/app/service/content.service';
import { DocumentService } from 'src/app/service/document.service';
import { FolderDocumentsViewModel } from 'src/app/usermodels/FolderDocumentsModel';
import { utility } from 'src/app/utility/utility';

@Component({
  selector: 'app-grid-documents',
  templateUrl: './grid-documents.component.html',
  styleUrls: ['./grid-documents.component.scss']
})
export class GridDocumentsComponent implements OnInit {

  @Input() documents: Documents[] = [];
  @Input() folders: Folders[] = [];
  @Input() folderId: number = 0;

  selectedDocument: Documents = new Documents();
  universes: Universes[] = [];

  constructor(private authService: AuthenticationService,
    private documentService: DocumentService, private contentService: ContentService,
    private router: Router) { }

  ngOnInit(): void {
    let accountId = (this.authService.getUser() as (Users)).id;

    this.documentService.getAllParentFolders(accountId).subscribe({
      next: (res) => {
        if (res != null) {
          var parentFolders = res;
          this.folders = parentFolders.concat(this.folders);
          if (this.folderId > 0) {
            this.documentService.getFolders(accountId, this.folderId).subscribe({
              next: (response) => {
                if (!this.folders.some(function (el) { return el.id === response.id }))
                  this.folders.push(response);
              }
            });
          }
        }
      }
    });

    let noUniverse = new Universes();
    noUniverse.id = 0;
    noUniverse.name = "No Universe";
    this.universes.push(noUniverse);
    this.contentService.getAllUniverses(accountId).subscribe({
      next: (res) => {
        console.log("getAllUniverses", res);
        this.universes.push(...res);
      }
    });
  }

  createDocument() {
    let accountId = (this.authService.getUser() as (Users)).id;
    let document: Documents = new Documents();
    document.body = "";
    document.title = "New Document";
    document.user_id = accountId;
    document.folder_id = this.folderId;

    this.documentService.addDocuments(document).subscribe({
      next: (res) => {
        console.log(res);
        this.router.navigate(["documents/" + res + "/edit"]);
      }
    });
  }

  viewDocument(id: any) {
    this.router.navigate(["documents/" + id]);
  }

  editDocument(id: any) {
    this.router.navigate(["documents/" + id + "/edit"]);
  }

  selectDocument(doc: Documents) {
    this.selectedDocument = doc;
  }


  onBlur($event: any, field_name: string) {
    console.log('blur', $event)
    console.log('blur', field_name)

    let model: BaseModel = new BaseModel();
    model._id = this.selectedDocument.id!;
    model.column_type = field_name;
    model.column_value = $event.target.value;
    model.content_type = "documents";
    this.contentService.saveData(model).subscribe({
      next: response => {
        console.log(response);
      }
    });
  }

  onModalChange($event: any) {
    if ($event == true) {
      console.log("true");
    }
    else {
      console.log("false");
    }
  }

  folderChanged($event: any) {
    console.log("$event", $event.target.value);

    let model: BaseModel = new BaseModel();
    model._id = this.selectedDocument.id!;
    model.column_type = "folder_id";
    model.column_value = $event.target.value;
    model.content_type = "documents";
    this.contentService.saveData(model).subscribe({
      next: response => {

        console.log(response);
      }
    });
  }

  universeChanged($event: any) {
    console.log("$event", $event.target.value);

    let model: BaseModel = new BaseModel();
    model._id = this.selectedDocument.id!;
    model.column_type = "universe_id";
    model.column_value = $event.target.value;
    model.content_type = "documents";
    this.contentService.saveData(model).subscribe({
      next: response => {

        console.log(response);
      }
    });
  }
}
