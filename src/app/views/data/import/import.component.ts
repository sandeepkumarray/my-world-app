import { isNull } from '@angular/compiler/src/output/output_ast';
import { Component, OnInit } from '@angular/core';
import { ContentTypes, Users } from 'src/app/model';
import { Buildings } from 'src/app/model/Buildings';
import { Characters } from 'src/app/model/Characters';
import { Conditions } from 'src/app/model/Conditions';
import { Attribute, ContentTemplateModel } from 'src/app/model/ContentTemplateModel';
import { Continents } from 'src/app/model/Continents';
import { Countries } from 'src/app/model/Countries';
import { Creatures } from 'src/app/model/Creatures';
import { Deities } from 'src/app/model/Deities';
import { Floras } from 'src/app/model/Floras';
import { Foods } from 'src/app/model/Foods';
import { Governments } from 'src/app/model/Governments';
import { Groups } from 'src/app/model/Groups';
import { Items } from 'src/app/model/Items';
import { Jobs } from 'src/app/model/Jobs';
import { Landmarks } from 'src/app/model/Landmarks';
import { Languages } from 'src/app/model/Languages';
import { Locations } from 'src/app/model/Locations';
import { Lores } from 'src/app/model/Lores';
import { Magics } from 'src/app/model/Magics';
import { Organizations } from 'src/app/model/Organizations';
import { Planets } from 'src/app/model/Planets';
import { Races } from 'src/app/model/Races';
import { Religions } from 'src/app/model/Religions';
import { Scenes } from 'src/app/model/Scenes';
import { Sports } from 'src/app/model/Sports';
import { Technologies } from 'src/app/model/Technologies';
import { Towns } from 'src/app/model/Towns';
import { Traditions } from 'src/app/model/Traditions';
import { Universes } from 'src/app/model/Universes';
import { Vehicles } from 'src/app/model/Vehicles';
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
  SelectedContentTypeAttributes: Attribute[] = [];
  alertMessage: string = "";
  userContentPlans: any;
  content_type_list: ContentTypes[] = [];
  attr_array: any[] = [];

  alertVisible = false;
  alertColor = 'danger';

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

      this.SelectedContentTypeAttributes = [];
      ContentTemplate.categories.map(c => {
        this.SelectedContentTypeAttributes.push(...c.attributes);
      });
      console.log("Attributes", this.SelectedContentTypeAttributes);

      let headers: any[] = [];
      this.SelectedContentTypeAttributes.map(a => {
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
          var excelJsondata = XLSX.utils.sheet_to_json(worksheet, { raw: true, defval: null });

          console.log("exceljsondata", excelJsondata);
          if (this.validateExcelData(excelJsondata)) {
            //this.uploadExcelData(excelJsondata);
          }
        }
        fileReader.readAsArrayBuffer(this.file);
      }
      else {
        this.addAlert("Select a File to upload.", 'danger');
      }
    }
    else {
      this.addAlert("Select a Content Type.", 'danger');
    }
  }

  uploadExcelData(excelJsonString: any) {
    let accountId = (this.authService.getUser() as (Users)).id;
    var saveObject: any;
    var saveAPIRef: any;

    excelJsonString.user_id = accountId;

    switch (this.selectedContentType) {
      case "Buildings":
        saveObject = excelJsonString as Buildings;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addBuilding(saveObject);
        break;

      case "Characters":
        saveObject = excelJsonString as Characters;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addCharacter(saveObject);
        break;

      case "Conditions":
        saveObject = excelJsonString as Conditions;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addCondition(saveObject);
        break;

      case "Continents":
        saveObject = excelJsonString as Continents;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addContinent(saveObject);
        break;

      case "Countries":
        saveObject = excelJsonString as Countries;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addCountrie(saveObject);
        break;

      case "Creatures":
        saveObject = excelJsonString as Creatures;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addCreature(saveObject);
        break;

      case "Deities":
        saveObject = excelJsonString as Deities;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addDeitie(saveObject);
        break;

      case "Floras":
        saveObject = excelJsonString as Floras;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addFlora(saveObject);
        break;

      case "Foods":
        saveObject = excelJsonString as Foods;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addFood(saveObject);
        break;

      case "Governments":
        saveObject = excelJsonString as Governments;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addGovernment(saveObject);
        break;

      case "Groups":
        saveObject = excelJsonString as Groups;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addGroup(saveObject);
        break;

      case "Items":
        saveObject = excelJsonString as Items;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addItem(saveObject);
        break;

      case "Jobs":
        saveObject = excelJsonString as Jobs;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addJob(saveObject);
        break;

      case "Landmarks":
        saveObject = excelJsonString as Landmarks;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addLandmark(saveObject);
        break;

      case "Languages":
        saveObject = excelJsonString as Languages;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addLanguage(saveObject);
        break;

      case "Locations":
        saveObject = excelJsonString as Locations;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addLocation(saveObject);
        break;

      case "Lores":
        saveObject = excelJsonString as Lores;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addLore(saveObject);
        break;

      case "Magics":
        saveObject = excelJsonString as Magics;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addMagic(saveObject);
        break;

      case "Planets":
        saveObject = excelJsonString as Planets;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addPlanet(saveObject);
        break;

      case "Races":
        saveObject = excelJsonString as Races;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addRace(saveObject);
        break;

      case "Religions":
        saveObject = excelJsonString as Religions;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addReligion(saveObject);
        break;

      case "Scenes":
        saveObject = excelJsonString as Scenes;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addScene(saveObject);
        break;

      case "Sports":
        saveObject = excelJsonString as Sports;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addSport(saveObject);
        break;

      case "Technologies":
        saveObject = excelJsonString as Technologies;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addTechnologie(saveObject);
        break;

      case "Towns":
        saveObject = excelJsonString as Towns;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addTown(saveObject);
        break;

      case "Traditions":
        saveObject = excelJsonString as Traditions;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addTradition(saveObject);
        break;

      case "Universes":
        saveObject = excelJsonString as Universes;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addUniverse(saveObject);
        break;

      case "Vehicles":
        saveObject = excelJsonString as Vehicles;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addVehicle(saveObject);
        break;

      case "Organizations":
        saveObject = excelJsonString as Organizations;
        saveObject.user_id = accountId;
        saveAPIRef = this.contentService.addOrganization(saveObject);
        break;
    }

    console.log("saveObject", saveObject);
    saveAPIRef.subscribe({
      next: (res: any) => {
        var user_id = accountId;
        var content_id = res.data;
        var name = excelJsonString.Name;
        var content_type = this.selectedContentType;
        var Universe = excelJsonString.Universe == '' ? null : (excelJsonString.Universe == null ? 0 : excelJsonString.Universe);

        this.myworldService.addUserContentAttributes(Universe, user_id, content_id, name, content_type).subscribe({
          next: (res: any) => {
            console.log("addUserContentAttributes", res);
          }
        });

        console.log("uploadExcelData result", res);
        //progress

        this.addAlert("File upload complete.", 'success');
      },
      error: (e: any) => {
        this.addAlert("File upload failed.", 'danger');
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
          this.addAlert("You have Exceeded the maximum allowed content for " + this.selectedContentType + ".", 'danger');
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

  addAlert(message: string, type: string) {
    this.alertVisible = true;
    this.alertMessage = message;
    this.alertColor = type;
  }
}
