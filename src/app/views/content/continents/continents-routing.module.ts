import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthGuard } from 'src/app/utility/AuthGuard';
import { ContinentsComponent } from './continents.component';
import { ViewContinentsComponent } from './view-continents/view-continents.component';
import { EditContinentsComponent } from './edit-continents/edit-continents.component';

const routes: Routes = [
  {
    path: '',
    data: {
      title: 'continents',
    },
    children: [
      {
        path: '',
        component: ContinentsComponent,
        data: {
          title: 'continents',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id',
        component: ViewContinentsComponent,
        data: {
          title: 'View Continent',
        },
        canActivate: [AuthGuard]
      },
      {
        path: ':id/edit',
        component: EditContinentsComponent,
        data: {
          title: 'Edit Continent',
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
export class ContinentsRoutingModule {

}
