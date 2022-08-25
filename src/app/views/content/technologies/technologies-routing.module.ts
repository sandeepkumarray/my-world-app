import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { TechnologiesComponent } from './technologies.component';
import { ViewTechnologiesComponent } from './view-technologies/view-technologies.component';
import { EditTechnologiesComponent } from './edit-technologies/edit-technologies.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'technologies',
    },
    children: [
      {
        path: '',
        component: TechnologiesComponent,
        data: {
          title: 'technologies',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewTechnologiesComponent,
        data: {
          title: 'View Technologie',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditTechnologiesComponent,
        data: {
          title: 'Edit Technologie',
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
export class TechnologiesRoutingModule {

}
