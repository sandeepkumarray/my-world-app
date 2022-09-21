import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { RouterModule, Routes } from '@angular/router';
import { DocumentsComponent } from './documents.component';
import { EditDocumentsComponent } from './edit-documents/edit-documents.component';
import { ViewDocumentsComponent } from './view-documents/view-documents.component';


const routes: Routes = [
  {
    path: '',
    data: {
      title: '',
    },
    children: [
      {
        path: '',
        component: DocumentsComponent,
        data: {
          title: 'Documents',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewDocumentsComponent,
        data: {
          title: 'View Documents',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditDocumentsComponent,
        data: {
          title: 'Edit Documents',
        },
        canActivate: [AuthGuard]
      },
    ]
  },
];


@NgModule({
  declarations: [], 
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class DocumentsRoutingModule { }
