import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { TownsComponent } from './towns.component';
import { ViewTownsComponent } from './view-towns/view-towns.component';
import { EditTownsComponent } from './edit-towns/edit-towns.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'towns',
    },
    children: [
      {
        path: '',
        component: TownsComponent,
        data: {
          title: 'towns',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewTownsComponent,
        data: {
          title: 'View Town',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditTownsComponent,
        data: {
          title: 'Edit Town',
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
export class TownsRoutingModule {

}
