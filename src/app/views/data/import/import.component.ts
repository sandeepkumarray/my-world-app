import { isNull } from '@angular/compiler/src/output/output_ast';
import { Component, OnInit } from '@angular/core';
import { ContentTypes, Users } from 'src/app/model';
import { Characters } from 'src/app/model/Characters';
import { Attribute, ContentTemplateModel } from 'src/app/model/ContentTemplateModel';
import { AuthenticationService } from 'src/app/service/authentication.service';
import { ContentService } from 'src/app/service/content.service';
import { MyworldService } from 'src/app/service/myworld.service';
import * as XLSX from 'xlsx';

@Component({
  selector: 'app-import',
  templateUrl: './import.component.html',
  styleUrls: ['./import.component.scss']
})
export class ImportComponent implements OnInit {
  file: any;
  selectedContentType: string = "";
  alertMessage: string = "";
  userContentPlans: any;
  content_type_list: ContentTypes[] = [];
  attr_array: any[] = [];

  alertVisible = false;

  constructor(private authService: AuthenticationService,
    private contentService: ContentService, private myworldService: MyworldService) {
    this.selectedContentType = "None";
  }

  ngOnInit(): void {
    let accountId = (this.authService.getUser() as (Users)).id;

    this.myworldService.getAllContentTypes().subscribe({
      next: (res) => {
        this.content_type_list = res;

      }
    });

    this.myworldService.getUserContentPlans(accountId).subscribe({
      next: (res) => {
        console.log(res);
        this.userContentPlans = res;
        console.log("userContentPlans", res);
      }
    });

  }

  contentTypeChanged($event: any) {
    this.attr_array = [];
    let accountId = (this.authService.getUser() as (Users)).id;
    this.selectedContentType = $event.target.value
    this.myworldService.getUsersContentTemplate(accountId).subscribe(res => {
      let contentTemplateModel = JSON.parse(res.template) as ContentTemplateModel;
      let ContentTemplate = contentTemplateModel.contents.find(c => c.content_type == this.selectedContentType.toLowerCase())!;
      ContentTemplate.categories = ContentTemplate.categories.sort((a, b) => a.order - b.order);

      let Attributes: Attribute[] = [];
      ContentTemplate.categories.map(c => {
        Attributes.push(...c.attributes);
      });
      console.log("Attributes", Attributes);

      let headers: any[] = [];
      Attributes.map(a => {
        headers.push(a.field_name);
      });
      this.attr_array.push(headers);
    });
  }

  onFileChange(args: any) {
    const self = this, file = args.srcElement && args.srcElement.files && args.srcElement.files[0];
    if (args.target.files.length > 0) {
      this.file = args.target.files[0];
    }
  }


  Upload() {
    if (this.selectedContentType != "None" && this.selectedContentType != "") {
      if (this.file != null) {
        let fileReader = new FileReader();
        fileReader.onload = (e) => {
          var arrayBuffer: ArrayBuffer = fileReader.result as ArrayBuffer;
          var data = new Uint8Array(arrayBuffer);
          var arr = new Array();
          for (var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
          var bstr = arr.join("");
          var workbook = XLSX.read(bstr, { type: "binary" });
          var first_sheet_name = workbook.SheetNames[0];
          var worksheet = workbook.Sheets[first_sheet_name];
          var excelJsondata = XLSX.utils.sheet_to_json(worksheet, { raw: true, defval: "" });

          console.log("exceljsondata", excelJsondata);
          if (this.validateExcelData(excelJsondata)) {
            this.uploadExcelData(excelJsondata);
          }
        }
        fileReader.readAsArrayBuffer(this.file);
      }
      else {
        this.addAlert("Select a File to upload.");
      }
    }
    else {
      this.addAlert("Select a Content Type.");
    }
  }

  uploadExcelData(excelJsonString: any) {
    let accountId = (this.authService.getUser() as (Users)).id;
    var saveObject: any;
    var saveAPIRef: any;

    switch (this.selectedContentType) {
      case "Characters":
        saveObject = excelJsonString as Characters;
        saveAPIRef = this.contentService.addCharacters(saveObject);
        break;
    }
    console.log("saveObject", saveObject);
    saveAPIRef.subscribe({
      next: (res: any) => {
        console.log(res);
        //progress
      }
    });
    // this.myworldService.createContent(this.selectedContentType, excelJsonString, accountId).subscribe({
    //   next: (res) => {
    //     console.log(res);
    //     //progress
    //   }
    // });
  }

  validateExcelData(excelJsondata: any): boolean {

    console.log("excelJsondata", excelJsondata);
    let accountId = (this.authService.getUser() as (Users)).id;

    this.myworldService.getDashboard(accountId).subscribe({
      next: (res) => {
        console.log(res);
        let dashboardModel = res;
        var content_item_count = dashboardModel[this.selectedContentType.toLowerCase() + '_total'];
        var planItemCount = this.userContentPlans[this.selectedContentType.toLowerCase() + '_count'];
        console.log("user content count : " + content_item_count);
        console.log("plan content count : " + planItemCount);

        let content_size: number = content_item_count + excelJsondata.length;

        console.log("content_size", content_size);
        if (content_size < planItemCount || this.userContentPlans.name == "Unlimited") {

          for (let row in excelJsondata) {
            let value = excelJsondata[row];
            console.log("row : " + row + " data : " + value)

            var excelJsonString = JSON.stringify(value);
            console.log("excelJsonString", excelJsonString);
            this.uploadExcelData(value);
            // var excelJsonObject = JSON.parse(excelJsonString);
            // var excelDataRows: { [key: string]: string } = {};
            // Object.keys(excelJsonObject).map(function (key) {
            //   excelDataRows[(key)] = excelJsonObject[key];
            // });
            // for (let row in excelDataRows) {
            //   let value = excelDataRows[row];
            //   //console.log("field : " + row + " value : " + value)
            // }

          }
        }
        else {
          this.addAlert("You have Exceeded the maximum allowed content for " + this.selectedContentType + ".");
        }
      }
    });
    return false;
  }

  downloadTemplate() {
    const ws: XLSX.WorkSheet = XLSX.utils.aoa_to_sheet(this.attr_array);

    /* generate workbook and add the worksheet */
    const wb: XLSX.WorkBook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, this.selectedContentType);

    /* save to file */
    XLSX.writeFile(wb, this.selectedContentType + ".xlsx");
  }

  exportExcel() {
    // import("xlsx").then(xlsx => {
    //     const worksheet = xlsx.utils.json_to_sheet(this.sales); // Sale Data
    //     const workbook = { Sheets: { 'data': worksheet }, SheetNames: ['data'] };
    //     const excelBuffer: any = xlsx.write(workbook, { bookType: 'xlsx', type: 'array' });
    //     this.saveAsExcelFile(excelBuffer, "sales");
    // });
  }

  saveAsExcelFile(buffer: any, fileName: string): void {
    // import("file-saver").then(FileSaver => {
    //   let EXCEL_TYPE =
    //     "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8";
    //   let EXCEL_EXTENSION = ".xlsx";
    //   const data: Blob = new Blob([buffer], {
    //     type: EXCEL_TYPE
    //   });
    //   FileSaver.saveAs(
    //     data,
    //     fileName + "_export_" + new Date().getTime() + EXCEL_EXTENSION
    //   );
    // });
  }

  addAlert(message: string) {
    this.alertVisible = true;
    this.alertMessage = message;
  }
}
