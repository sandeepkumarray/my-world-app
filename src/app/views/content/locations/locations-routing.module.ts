import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { LocationsComponent } from './locations.component';
import { ViewLocationsComponent } from './view-locations/view-locations.component';
import { EditLocationsComponent } from './edit-locations/edit-locations.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'locations',
    },
    children: [
      {
        path: '',
        component: LocationsComponent,
        data: {
          title: 'locations',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewLocationsComponent,
        data: {
          title: 'View Location',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditLocationsComponent,
        data: {
          title: 'Edit Location',
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
export class LocationsRoutingModule {

}
