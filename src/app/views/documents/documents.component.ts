import { Component, OnInit } from '@angular/core';
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
  selector: 'app-documents',
  templateUrl: './documents.component.html',
  styleUrls: ['./documents.component.scss']
})
export class DocumentsComponent implements OnInit {

  selectedDocument: Documents = new Documents();
  universes: Universes[] = [];
  viewModel: FolderDocumentsViewModel = new FolderDocumentsViewModel;

  constructor(private authService: AuthenticationService,
    private documentService: DocumentService,
    private contentService: ContentService,
    private router: Router) { }

  ngOnInit(): void {
    let accountId = (this.authService.getUser() as (Users)).id;

    this.documentService.getAllDocuments(accountId).subscribe({
      next: (res) => {
        if (res != null)
          res.map(d => {
            d.timeSince = d.updated_at == null ? utility.timeSince(new Date(d.created_at!)) : utility.timeSince(new Date(d.updated_at!));
            d.createdSince = utility.timeSince(new Date(d.created_at!));
            d.readingTime = utility.readingTimeWithCount(d.cached_word_count!);
            this.viewModel.documents.push(d);
            this.viewModel.documents = this.viewModel.documents.sort((a, b) => a.id! - b.id!);
          });
      }
    });

    this.documentService.getAllParentFolders(accountId).subscribe({
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
    document.folder_id = 0;

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

  addFolder() {
    let accountId = (this.authService.getUser() as (Users)).id;

    let folder: Folders = new Folders();
    folder.title = "New Folder";
    folder.context="";
    folder.parent_folder_id = 0;
    folder.user_id =accountId;
    this.documentService.addFolders(folder).subscribe({
      next: response => {

        console.log(response);
        this.router.navigate(["folders/"+response]);
      }
    });
  }
}