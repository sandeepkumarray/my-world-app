import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router';
import { ImportComponent } from './import/import.component';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { DataComponent } from './data.component';


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
