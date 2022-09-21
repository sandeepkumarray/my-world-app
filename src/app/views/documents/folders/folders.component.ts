import { Component, OnInit } from '@angular/core';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentService } from 'src/app/service/content.service';
import { DocumentService } from 'src/app/service/document.service';
import { ActivatedRoute, Router } from '@angular/router';
import { Documents, Folders, Users } from 'src/app/model';
import { FolderDocumentsModel } from 'src/app/usermodels/FolderDocumentsModel';
import { Location, LocationStrategy, PathLocationStrategy } from '@angular/common';
import { BaseModel } from 'src/app/model/BaseModel';
@Component({
  selector: 'app-folders',
  templateUrl: './folders.component.html',
  styleUrls: ['./folders.component.scss']
})
export class FoldersComponent implements OnInit {
  id: any = "";

  viewModel: FolderDocumentsModel = new FolderDocumentsModel;
  foldersModal: Folders[] = [];

  constructor(private activatedRoute: ActivatedRoute,
    private authService: AuthenticationService,
    private documentService: DocumentService,
    private contentService: ContentService,
    private router: Router,
    private location: Location) {
    this.id = this.activatedRoute.snapshot.paramMap.get('id');
  }

  ngOnInit(): void {

    let accountId = (this.authService.getUser() as (Users)).id;
    this.documentService.getFolders(accountId, this.id).subscribe({
      next: (res) => {
        if (res != null) {
          this.viewModel.id = res.id;
          this.viewModel.title = res.title;
          this.viewModel.id = res.id;
          this.viewModel = Object.assign(this.viewModel, res);
          console.log(this.viewModel);
        }
      }
    });

    this.documentService.getAllDocumentsForFolderId(accountId, this.id).subscribe({
      next: (res) => {
        if (res != null)
          this.viewModel.documents = res;
      }
    });

    let noFolder = new Folders();
    noFolder.id = 0;
    noFolder.title = "None";
    this.foldersModal.push(noFolder);
    this.documentService.getAllParentFolders(accountId).subscribe({
      next: (res) => {
        if (res != null) {
          this.foldersModal.push(...res);
        }
      }
    });

    this.documentService.getAllChildFolders(accountId, this.id).subscribe({
      next: (res) => {
        if (res != null)
          this.viewModel.folders = res;
      }
    });
  }

  createDocument() {
    let accountId = (this.authService.getUser() as (Users)).id;
    let document: Documents = new Documents();
    document.body = "";
    document.title = "New Document";
    document.user_id = accountId;
    document.folder_id = this.id;

    this.documentService.addDocuments(document).subscribe({
      next: (res) => {
        console.log(res);
        this.router.navigate(["documents/" + res + "/edit"]);
      }
    });
  }

  back() {
    window.history.back();
  }

  gotoFolder(id: number) {

    this.router.navigate(["folders/" + id]);
  }

  onBlur($event: any, field_name: string) {

    let model: BaseModel = new BaseModel();
    model._id = this.viewModel.id!;
    model.column_type = field_name;
    model.column_value = $event.target.value;
    model.content_type = "folders";
    this.contentService.saveData(model).subscribe({
      next: response => {
        console.log(response);
      }
    });
  }

  folderChanged($event: any) {
    console.log("$event", $event.target.value);

    let model: BaseModel = new BaseModel();
    model._id = this.viewModel.id!;
    model.column_type = "parent_folder_id";
    model.column_value = $event.target.value;
    model.content_type = "folders";
    this.contentService.saveData(model).subscribe({
      next: response => {

        console.log(response);
      }
    });
  }

  addFolder() {
    let accountId = (this.authService.getUser() as (Users)).id;

    let folder: Folders = new Folders();
    folder.title = "New Folder";
    folder.context="";
    folder.parent_folder_id = this.id;
    folder.user_id =accountId;
    this.documentService.addFolders(folder).subscribe({
      next: response => {

        console.log(response);
        this.router.navigate(["folders/"+response]);
      }
    });
  }

  deleteFolder(option: string) {

    console.log("option", option);


    if (option == "YES") {

      //move child folders to top level    
      this.documentService.UpdateChildFoldersToTop(this.id).subscribe({
        next: response => {

          console.log(response);
        }
      });

      //update documents with null folder_id
      this.documentService.updateDocumentsFolderToNull(this.id).subscribe({
        next: response => {

          console.log(response);
        }
      });

      //delete folder
      let folder: Folders = new Folders();
      folder.id = this.id;
      this.documentService.deleteFolders(folder).subscribe({
        next: response => {

          console.log(response);
        }
      });

      this.router.navigate(["documents"]);
    }
  }
}
