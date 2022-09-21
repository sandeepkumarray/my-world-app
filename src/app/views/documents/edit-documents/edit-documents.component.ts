import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Documents, Users } from 'src/app/model';
import { BaseModel } from 'src/app/model/BaseModel';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentService } from 'src/app/service/content.service';
import { DocumentService } from 'src/app/service/document.service';
import 'quill-mention'
import { constants } from 'src/app/utility/constants';

@Component({
  selector: 'app-edit-documents',
  templateUrl: './edit-documents.component.html',
  styleUrls: ['./edit-documents.component.scss']
})
export class EditDocumentsComponent implements OnInit {
  id: any = "";
  document: Documents = new Documents();
  status:string = "";

  modules = {
    toolbar: true,
    mention: {
      allowedChars: /^[A-Za-z\sÅÄÖåäö]*$/,
      mentionDenotationChars: ["@"],
      showDenotationChar: false,
      onSelect: (item: any, insertItem: (arg0: any) => void) => {
        // const editor = this.editor.quillEditor;
        insertItem(item);
        // // necessary because quill-mention triggers changes as 'api' instead of 'user'
        // editor.insertText(editor.getLength() - 1, "", "user");
      },
      source: (searchTerm: string, renderList: (arg0: any[], arg1: any) => void) => {
        let accountId = (this.authService.getUser() as (Users)).id;
        this.documentService.getAllMentions(accountId).subscribe({
          next: response => {
            console.log(response);
            let matches: any[] = [];
            response.map(m => {
              let content: any = {};
              content.id = m.id;
              content.icon = m.icon!.replace("fa-3x", "fa-2x");
              content.label = m.name;
              content.link = "#/" + m.content_type + "/" + m.id;
              content.value = "[" + m.content_type + "-" + m.id + ":" + m.name + "]";
              matches.push(content);
            });
            //renderList(response.filter(content => content.name.includes(searchTerm)));
            renderList(matches.filter(content => content.value.includes(searchTerm)), searchTerm);
          }
        });
      },
      renderItem: (item: any, searchTerm: any) => {
        console.log(item);
        return '<div class="d-flex justify-content-between align-items-center"><span style="font-size: 14px;">' + item.label + '</span><span style="margin-left:20px">' + item.icon + '</span></div>';
      }
    },
    counter: { container: '#counter', unit: 'word' }
  };

  constructor(private activatedRoute: ActivatedRoute,
    private authService: AuthenticationService,
    private documentService: DocumentService,
    private contentService: ContentService,private router: Router,) {
    this.id = this.activatedRoute.snapshot.paramMap.get('id');
  }

  ngOnInit(): void {
    let accountId = (this.authService.getUser() as (Users)).id;
    this.status=constants.documentSave;
    this.documentService.getDocuments(accountId, this.id).subscribe({
      next: response => {
        this.document = response;
        console.log("document", this.document);
      }
    });
  }

  onBlur($event: any, field_name: string) {
    let model: BaseModel = new BaseModel();
    model._id = this.id;
    model.column_type = field_name;
    model.column_value = $event.target.value;
    model.content_type = "documents";
    this.contentService.saveData(model).subscribe({
      next: response => {
      }
    });
  }

  onQuillBlur($event: any) {

    this.status = "Saving...";
    let model: BaseModel = new BaseModel();
    model._id = this.id;
    model.column_type = "body";
    model.column_value = $event.editor.root.innerHTML;
    model.content_type = "documents";
    this.contentService.saveData(model).subscribe({
      next: response => {
        this.status = "Saved!!";
      }
    });

    var counter = document.getElementById("counter")?.innerHTML;
    var count = parseInt(counter!);
    model = new BaseModel();
    model._id = this.id;
    model.column_type = "cached_word_count";
    model.column_value = count;
    model.content_type = "documents";
    this.contentService.saveData(model).subscribe({
      next: response => {
      }
    });
  }

  deleteDocument(){
    this.documentService.deleteDocuments(this.document).subscribe({
      next: response => {
        this.router.navigate(["documents"]);
      }
    });
  }
}
