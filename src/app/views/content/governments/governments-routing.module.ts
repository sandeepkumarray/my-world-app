import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { GovernmentsComponent } from './governments.component';
import { ViewGovernmentsComponent } from './view-governments/view-governments.component';
import { EditGovernmentsComponent } from './edit-governments/edit-governments.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'governments',
    },
    children: [
      {
        path: '',
        component: GovernmentsComponent,
        data: {
          title: 'governments',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewGovernmentsComponent,
        data: {
          title: 'View Government',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditGovernmentsComponent,
        data: {
          title: 'Edit Government',
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
export class GovernmentsRoutingModule {

}
