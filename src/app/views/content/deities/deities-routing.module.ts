import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { DeitiesComponent } from './deities.component';
import { ViewDeitiesComponent } from './view-deities/view-deities.component';
import { EditDeitiesComponent } from './edit-deities/edit-deities.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'deities',
    },
    children: [
      {
        path: '',
        component: DeitiesComponent,
        data: {
          title: 'deities',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewDeitiesComponent,
        data: {
          title: 'View Deitie',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditDeitiesComponent,
        data: {
          title: 'Edit Deitie',
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
export class DeitiesRoutingModule {

}
