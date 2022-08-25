import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { PlanetsComponent } from './planets.component';
import { ViewPlanetsComponent } from './view-planets/view-planets.component';
import { EditPlanetsComponent } from './edit-planets/edit-planets.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'planets',
    },
    children: [
      {
        path: '',
        component: PlanetsComponent,
        data: {
          title: 'planets',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewPlanetsComponent,
        data: {
          title: 'View Planet',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditPlanetsComponent,
        data: {
          title: 'Edit Planet',
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
export class PlanetsRoutingModule {

}
