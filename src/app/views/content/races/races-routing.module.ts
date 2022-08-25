import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { RacesComponent } from './races.component';
import { ViewRacesComponent } from './view-races/view-races.component';
import { EditRacesComponent } from './edit-races/edit-races.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'races',
    },
    children: [
      {
        path: '',
        component: RacesComponent,
        data: {
          title: 'races',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewRacesComponent,
        data: {
          title: 'View Race',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditRacesComponent,
        data: {
          title: 'Edit Race',
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
export class RacesRoutingModule {

}
