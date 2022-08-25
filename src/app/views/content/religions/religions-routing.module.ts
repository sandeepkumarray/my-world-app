import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { ReligionsComponent } from './religions.component';
import { ViewReligionsComponent } from './view-religions/view-religions.component';
import { EditReligionsComponent } from './edit-religions/edit-religions.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'religions',
    },
    children: [
      {
        path: '',
        component: ReligionsComponent,
        data: {
          title: 'religions',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewReligionsComponent,
        data: {
          title: 'View Religion',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditReligionsComponent,
        data: {
          title: 'Edit Religion',
        },
        canActivate: [AuthGuard]
      },
    ]
  },
];


@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ReligionsRoutingModule {

}
