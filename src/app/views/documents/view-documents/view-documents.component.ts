import { Component, OnInit } from '@angular/core';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentService } from 'src/app/service/content.service';
import { DocumentService } from 'src/app/service/document.service';
import 'quill-mention'
import { ActivatedRoute, Router } from '@angular/router';
import { Documents, Users } from 'src/app/model';
import { from, map, mergeMap } from 'rxjs';
import { parse } from 'node-html-parser';

@Component({
  selector: 'app-view-documents',
  templateUrl: './view-documents.component.html',
  styleUrls: ['./view-documents.component.scss']
})
export class ViewDocumentsComponent implements OnInit {
  id: any = "";
  title: string = "";
  documentBody: string = "";


  constructor(private activatedRoute: ActivatedRoute,
    private authService: AuthenticationService,
    private documentService: DocumentService,
    private contentService: ContentService,
    private router: Router) {
    this.id = this.activatedRoute.snapshot.paramMap.get('id');
  }

  ngOnInit(): void {
    let accountId = (this.authService.getUser() as (Users)).id;
    this.documentService.getDocuments(accountId, this.id).subscribe({
      next: response => {
        this.title = response.title!;
        let htmlBody: string = response.body;
        let parsedHtml = parse(htmlBody);
        var parsedAnchors = parsedHtml.getElementsByTagName("a");
        parsedAnchors = parsedAnchors.filter((x) => { return this.checkIfMentionTagHTMLElement(x) });
        if (parsedAnchors.length > 0) {
          parsedAnchors.map((item, index) => {
            var oldValue = item.innerHTML;
            if (this.checkIfMentionTag(oldValue)) {
              var contentLabel = oldValue.split(':')[0];
              contentLabel = contentLabel.replace("[", "");
              var contentType = contentLabel.split('-')[0];
              var contentID = contentLabel.split('-')[1];
              this.contentService.getContentDetailsFromTypeID(contentType, contentID).pipe(
                map((res) => {         // <-- map back to original item with added type
                  let contentTypeName = contentType.substring(0, contentType.length - 1);
                  parsedAnchors[index].classList.add(contentTypeName + "-link");
                  parsedAnchors[index].innerHTML = res.content_name;

                })
              ).subscribe({
                next: response => {
                  this.documentBody = parsedHtml.outerHTML;
                }
              });
            }
          });
        }
        else {
          this.documentBody = parsedHtml.outerHTML;
        }
      }
    });
  }

  editDocument(id: any) {
    this.router.navigate(["documents/" + id + "/edit"]);
  }

  checkIfMentionTag(value: string) {
    if (value.startsWith("[") && value.endsWith("]") && value.includes(":") && value.includes("-"))
      return true;
    else
      return false;
  }

  checkIfMentionTagHTMLElement(value: any) {
    if (value.innerHTML.startsWith("[") && value.innerHTML.endsWith("]") && value.innerHTML.includes(":") && value.innerHTML.includes("-"))
      return true;
    else
      return false;
  }
}
