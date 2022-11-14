import { Component, OnInit } from '@angular/core';
import { FileSaverService } from 'ngx-filesaver';
import { ContentTypes, Documents, Users } from 'src/app/model';
import { Attribute, Content, ContentTemplateModel } from 'src/app/model/ContentTemplateModel';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentService } from 'src/app/service/content.service';
import { DocumentService } from 'src/app/service/document.service';
import { MyworldService } from 'src/app/service/myworld.service';
import { utility } from 'src/app/utility/utility';
import * as XLSX from 'xlsx';
import * as JSZip from 'jszip';
import { asBlob } from 'html-docx-js-typescript';
import { FileDetails } from 'src/app/model/FileDetails';
import * as FileSaver from 'file-saver';


@Component({
  selector: 'app-export',
  templateUrl: './export.component.html',
  styleUrls: ['./export.component.scss']
})
export class ExportComponent implements OnInit {

  content_type_list!: ContentTypes[];
  content_template!: Content[];
  fileDetails: FileDetails[] = [];
  documents: Documents[] = [];

  constructor(
    private myworldService: MyworldService,
    private contentService: ContentService,
    private authService: AuthenticationService,
    private documentService: DocumentService,
    private fileSaverService: FileSaverService) { }

  ngOnInit(): void {
    let accountId = (this.authService.getUser() as (Users)).id;

    this.myworldService.getAllContentTypes().subscribe({
      next: (res) => {
        this.content_type_list = res;
        console.log("content_type_list", res);
      }
    });

    this.myworldService.getUsersContentTemplate(accountId).subscribe(res => {
      let contentTemplateModel = JSON.parse(res.template) as ContentTemplateModel;
      this.content_template = contentTemplateModel.contents;
    });

    this.documentService.getAllDocuments(accountId).subscribe({
      next: async (res) => {
        if (res != null) {
          res.forEach(async (_value: Documents, i: any) => {
            console.log("downloadDocumentsData 2");
            await asBlob(_value.body).then((dblob) => {
              _value.docblob = dblob;
            });
          });
          this.documents = res;
        }
      }
    });
  }

  downloadExcelData(contentType: any) {
    let accountId = (this.authService.getUser() as (Users)).id;
    this.contentService.getAllContentTypeDataForUser(accountId, contentType).subscribe({
      next: (res) => {
        if (res != null) {
          const myworksheet: XLSX.WorkSheet = XLSX.utils.json_to_sheet(res);
          const myworkbook: XLSX.WorkBook = { Sheets: { 'data': myworksheet }, SheetNames: ['data'] };
          /* save to file */
          XLSX.writeFile(myworkbook, contentType + ".xlsx", { bookType: 'xlsx', type: 'array' });
          console.log("getAllContentTypeDataForUser", res);
        }
      }
    });
  }

  downloadJsonData() {
    let accountId = (this.authService.getUser() as (Users)).id;
    this.contentService.getAllContentDataForUser(accountId).subscribe({
      next: (res) => {
        var jsonResult = "";
        if (res != null) {
          let contentData: any[] = [];
          res.map((c: any) => {
            contentData.push(c);
          });
          let grouped = utility.groupByKey(contentData, 'content_type');
          jsonResult = JSON.stringify(grouped);
        }
        this.downloadText(jsonResult, 'myworld.json');
      }
    });
  }

  downloadXMLData() {
    let accountId = (this.authService.getUser() as (Users)).id;
    this.contentService.getAllContentDataForUser(accountId).subscribe({
      next: (res) => {
        let finalXML = '<contents>';
        if (res != null) {

          let contentData: any[] = [];
          res.map((c: any) => {
            contentData.push(c);
          });

          let grouped = utility.groupByKey(contentData, 'content_type');
          const keysofgrouped = Object.keys(grouped);

          for (let i = 0; i < Object.keys(grouped).length; i++) {
            let key = keysofgrouped[i];
            console.log("key at i = " + i, key);
            console.log("i = " + i, grouped[key]);
            finalXML = finalXML.concat('<' + key + ' type="array">');
            let contentArray = grouped[key];
            let contentXML = this.toXML(contentArray, key);
            finalXML = finalXML.concat(contentXML);
            finalXML = finalXML.concat('</' + key + '>');
          }
        }
        finalXML = finalXML.concat('</contents>');
        this.downloadText(finalXML, 'myworld.xml');
      }
    });
  }

  toXML(array: any[], type: any) {
    let xmlString = '';
    array
      .reduce((hash, obj) => {
        console.log("toXML obj length ", Object.keys(obj));
        xmlString = xmlString.concat('<' + type + '>');
        for (var prop of Object.keys(obj)) {
          console.log("toXML for prop ", prop);
          console.log("toXML for prop value", obj[prop]);
          if (obj[prop] != null && obj[prop] != '<p><br></p>') {

            xmlString = xmlString.concat('<' + prop + '>');
            xmlString = xmlString.concat(obj[prop]);
            xmlString = xmlString.concat('</' + prop + '>');
          }
        }
        xmlString = xmlString.concat('</' + type + '>');
      }, {});
    return xmlString;
  }

  downloadTXTData() {
    let accountId = (this.authService.getUser() as (Users)).id;
    this.contentService.getAllContentDataForUser(accountId).subscribe({
      next: (res) => {
        console.log("getAllContentDataForUser", res);
        var TXTResult = "";
        if (res != null) {

          let contentData: any[] = [];
          res.map((c: any) => {
            contentData.push(c);
          });

          console.log("contentData", contentData);
          const myworksheet: XLSX.WorkSheet = XLSX.utils.json_to_sheet(res);
          TXTResult = XLSX.utils.sheet_to_txt(myworksheet);
        }
        this.downloadText(TXTResult, 'myworld.txt');
      }
    });
  }

  downloadText(text: any, fileName: string) {
    var data = new Blob([text], { type: 'text/plain;charset=utf-8' });
    this.fileSaverService.save(data, fileName);
  }

  downloadDocumentsData() {
    let accountId = (this.authService.getUser() as (Users)).id;
    const name = 'documents.zip';
    var zip = new JSZip();

    console.log("downloadDocumentsData this.documents", this.documents);
    this.documents.forEach((_value: Documents, i: any) => {
      zip!.file(_value.title! + ".docx", _value.docblob!, { binary: true });
    });

    zip.generateAsync({ type: "blob" }).then((content) => {
      this.fileSaverService.save(content, name);
    });
  }

  async createZip(fileBlobs: FileDetails[], zipName: string) {
    const zip = new JSZip();
    const name = zipName + '.zip';

    for (let counter = 0; counter < fileBlobs.length; counter++) {
      const b = fileBlobs[counter];
      zip.file(b.name!, b.fileBlob!);
    }

    setTimeout(async () => {
      await zip.generateAsync({ type: 'blob' }).then((content) => {
        if (content) {
          this.fileSaverService.save(content, name);
        }
      });
    }, 3000);

  }

  back() {
    window.history.back();
  }
}
