import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { BuildingsComponent } from './buildings.component';
import { ViewBuildingsComponent } from './view-buildings/view-buildings.component';
import { EditBuildingsComponent } from './edit-buildings/edit-buildings.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'buildings',
    },
    children: [
      {
        path: '',
        component: BuildingsComponent,
        data: {
          title: 'buildings',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewBuildingsComponent,
        data: {
          title: 'View Building',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditBuildingsComponent,
        data: {
          title: 'Edit Building',
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
export class BuildingsRoutingModule {

}
