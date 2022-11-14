import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router';
import { ImportComponent } from './import/import.component';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { DataComponent } from './data.component';
import { ExportComponent } from './export/export.component';
import { UsageComponent } from './usage/usage.component';
import { ImagesManageComponent } from './images-manage/images-manage.component';


const routes: Routes = [
  {
    path: '',
    data: {
      title: '',
    },
    children: [
      {
        path: '',
        component: DataComponent,
        data: {
          title: 'Data',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'import',
        component: ImportComponent,
        data: {
          title: 'Data Import',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'export',
        component: ExportComponent,
        data: {
          title: 'Data Export',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'usage',
        component: UsageComponent,
        data: {
          title: 'Data Usage',
        },
        canActivate: [AuthGuard]
      },
      {
        path: 'manage',
        component: ImagesManageComponent,
        data: {
          title: 'Images Management',
        },
        canActivate: [AuthGuard]
      }
    ]
  },
];

@NgModule({
  declarations: [], 
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class DataRoutingModule { }
