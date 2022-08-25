import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { VehiclesComponent } from './vehicles.component';
import { ViewVehiclesComponent } from './view-vehicles/view-vehicles.component';
import { EditVehiclesComponent } from './edit-vehicles/edit-vehicles.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'vehicles',
    },
    children: [
      {
        path: '',
        component: VehiclesComponent,
        data: {
          title: 'vehicles',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewVehiclesComponent,
        data: {
          title: 'View Vehicle',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditVehiclesComponent,
        data: {
          title: 'Edit Vehicle',
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
export class VehiclesRoutingModule {

}
